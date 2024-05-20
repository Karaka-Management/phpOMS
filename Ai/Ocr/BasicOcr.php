<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Ai\Ocr
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Ai\Ocr;

use phpOMS\Math\Topology\MetricsND;
use phpOMS\System\File\PathException;

/**
 * Basic OCR implementation for MNIST data
 *
 * @package phpOMS\Ai\Ocr
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BasicOcr
{
    /**
     * Dataset on which the OCR is trained on.
     *
     * The data needs to be MNIST data.
     *
     * @var array
     * @since 1.0.0
     */
    private array $Xtrain = [];

    /**
     * Resultset on which the OCR is trained on.
     *
     * These are the actual values for the Xtrain data and must therefore have the same dimension.
     *
     * The labels need to be MNIST labels.
     *
     * @var array
     * @since 1.0.0
     */
    private array $ytrain = [];

    /**
     * Train OCR with data and result/labels
     *
     * @param string $dataPath  Impage path to read
     * @param string $labelPath Label path to read
     * @param int    $limit     Limit (0 = unlimited)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function trainWith(string $dataPath, string $labelPath, int $limit = 0) : void
    {
        $Xtrain = $this->readImages($dataPath, $limit);
        $ytrain = $this->readLabels($labelPath, $limit);

        $this->Xtrain = \array_merge($this->Xtrain, $Xtrain);
        $this->ytrain = \array_merge($this->ytrain, $ytrain);
    }

    /**
     * Read image from path
     *
     * @param string $path  Image to read
     * @param int    $limit Limit
     *
     * @return array
     *
     * @throws PathException
     *
     * @since 1.0.0
     */
    private function readImages(string $path, int $limit = 0) : array
    {
        if (!\is_file($path)) {
            throw new PathException($path);
        }

        $fp = \fopen($path, 'r');
        if ($fp === false) {
            throw new PathException($path); // @codeCoverageIgnore
        }

        if (($read = \fread($fp, 4)) === false || ($unpack = \unpack('N', $read)) === false) {
            return []; // @codeCoverageIgnore
        }

        // $magicNumber = $unpack[1];
        // 2051 === image data (should always be this)
        // 2049 === label data

        if (($read = \fread($fp, 4)) === false || ($unpack = \unpack('N', $read)) === false) {
            return []; // @codeCoverageIgnore
        }
        $numberOfImages = $unpack[1];

        if ($limit > 0) {
            $numberOfImages = \min($numberOfImages, $limit);
        }

        if (($read = \fread($fp, 4)) === false || ($unpack = \unpack('N', $read)) === false) {
            return []; // @codeCoverageIgnore
        }

        /** @var int<0, max> $numberOfRows */
        $numberOfRows = (int) $unpack[1];

        if (($read = \fread($fp, 4)) === false || ($unpack = \unpack('N', $read)) === false) {
            return []; // @codeCoverageIgnore
        }

        /** @var int<0, max> $numberOfColumns */
        $numberOfColumns = (int) $unpack[1];

        $images = [];
        for ($i = 0; $i < $numberOfImages; ++$i) {
            if (($read = \fread($fp, $numberOfRows * $numberOfColumns)) === false
                || ($unpack = \unpack('C*', $read)) === false
            ) {
                return []; // @codeCoverageIgnore
            }

            $images[] = \array_values($unpack);
        }

        \fclose($fp);

        return $images;
    }

    /**
     * Read labels from from path
     *
     * @param string $path  Labels path
     * @param int    $limit Limit
     *
     * @return array
     *
     * @throws PathException
     *
     * @since 1.0.0
     */
    private function readLabels(string $path, int $limit = 0) : array
    {
        if (!\is_file($path)) {
            throw new PathException($path);
        }

        $fp = \fopen($path, 'r');
        if ($fp === false) {
            throw new PathException($path); // @codeCoverageIgnore
        }

        if (($read = \fread($fp, 4)) === false || ($unpack = \unpack('N', $read)) === false) {
            return []; // @codeCoverageIgnore
        }

        // $magicNumber = $unpack[1];
        // 2051 === image data
        // 2049 === label data (should always be this)

        if (($read = \fread($fp, 4)) === false || ($unpack = \unpack('N', $read)) === false) {
            return []; // @codeCoverageIgnore
        }
        $numberOfLabels = $unpack[1];

        if ($limit > 0) {
            $numberOfLabels = \min($numberOfLabels, $limit);
        }

        $labels = [];
        for ($i = 0; $i < $numberOfLabels; ++$i) {
            if (($read = \fread($fp, 1)) === false || ($unpack = \unpack('C', $read)) === false) {
                return []; // @codeCoverageIgnore
            }
            $labels[] = $unpack[1];
        }

        \fclose($fp);

        return $labels;
    }

    /**
     * Find the k-nearest matches for test data
     *
     * @param array $Xtrain Image data used for training
     * @param array $ytrain Labels associated with the trained data
     * @param array $Xtest  Image data from the image to categorize
     * @param int   $k      Amount of best fits that should be found
     */
    private function kNearest(array $Xtrain, array $ytrain, array $Xtest, int $k = 3) : array
    {
        $predictedLabels = [];
        foreach ($Xtest as $sample) {
            $distances = $this->getDistances($Xtrain, $sample);
            \asort($distances);

            $keys = \array_keys($distances);

            $candidateLabels = [];
            for ($i = 0; $i < $k; ++$i) {
                $candidateLabels[] = $ytrain[$keys[$i]];
            }

            // find best match
            $countedCandidates = \array_count_values($candidateLabels);

            foreach ($candidateLabels as $i => $label) {
                $predictedLabels[] = [
                    'label' => $label,
                    'prob'  => $countedCandidates[$label] / $k,
                ];
            }
        }

        return $predictedLabels;
    }

    /**
     * Fitting method in order to see how similar two datasets are.
     *
     * @param array $Xtrain Image data used for training
     * @param array $sample Image data to compare against
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function getDistances(array $Xtrain, array $sample) : array
    {
        $dist = [];
        foreach ($Xtrain as $train) {
            $dist[] = MetricsND::euclidean($train, $sample);
        }

        return $dist;
    }

    /**
     * Create MNIST file from images
     *
     * @param string[] $images     Images
     * @param string   $out        Output file
     * @param int      $resolution Resolution of the iomages
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function imagesToMNIST(array $images, string $out, int $resolution) : void
    {
        $out = \fopen($out, 'wb');
        if ($out === false) {
            return; // @codeCoverageIgnore
        }

        \fwrite($out, \pack('N', 2051));
        \fwrite($out, \pack('N', \count($images)));
        \fwrite($out, \pack('N', $resolution));
        \fwrite($out, \pack('N', $resolution));

        $size = $resolution * $resolution;

        foreach ($images as $in) {
            $inString = \file_get_contents($in);
            if ($inString === false) {
                continue;
            }

            $im = \imagecreatefromstring($inString);
            if ($im === false) {
                continue;
            }

            $new = \imagescale($im, $resolution, $resolution);
            if ($new === false) {
                continue;
            }

            // Convert the image to grayscale and normalize the pixel values
            $mnist = [];
            for ($i = 0; $i < $resolution; ++$i) {
                for ($j = 0; $j < $resolution; ++$j) {
                    $pixel = \imagecolorat($new, $j, $i);
                    $gray  = \round(
                        (
                            0.299 * (($pixel >> 16) & 0xFF)
                            + 0.587 * (($pixel >> 8) & 0xFF)
                            + 0.114 * ($pixel & 0xFF)
                        ) / 255,
                        3
                    );

                    $mnist[] = $gray;
                }
            }

            for ($i = 0; $i < $size; ++$i) {
                \fwrite($out, \pack('C', (int) \round($mnist[$i] * 255)));
            }
        }

        \fclose($out);
    }

    /**
     * Convert labels to MNIST format
     *
     * @param string[] $data Labels (one char per label)
     * @param string   $out  Output path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function labelsToMNIST(array $data, string $out) : void
    {
        // Only allows single char labels
        $out = \fopen($out, 'wb');
        if ($out === false) {
            return; // @codeCoverageIgnore
        }

        \fwrite($out, \pack('N', 2049));
        \fwrite($out, \pack('N', \count($data)));

        foreach ($data as $e) {
            \fwrite($out, \pack('C', $e));
        }

        \fclose($out);
    }

    /**
     * Categorize an unknown image
     *
     * @param string $path       Path to the image to categorize/evaluate/match against the training data
     * @param int    $comparison Amount of comparisons
     * @param int    $limit      Limit (0 = unlimited)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function matchImage(string $path, int $comparison = 3, int $limit = 0) : array
    {
        $Xtest = $this->readImages($path, $limit);

        return $this->kNearest($this->Xtrain, $this->ytrain, $Xtest, $comparison);
    }
}
