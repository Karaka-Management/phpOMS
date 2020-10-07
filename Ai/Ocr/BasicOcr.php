<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Ai\Ocr
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Ai\Ocr;

use phpOMS\Math\Topology\MetricsND;
use phpOMS\System\File\PathException;

/**
 * Basic OCR implementation for MNIST data
 *
 * @package phpOMS\Ai\Ocr
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
     * @param string $limit     Limit (0 = unlimited)
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
     * Reat image from path
     *
     * @param string $path  Image to read
     * @param string $limit Limit
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function readImages(string $path, int $limit = 0) : array
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $fp = \fopen($path, 'r');
        if ($fp === false) {
            throw new PathException($path); // @codeCoverageIgnore
        }

        $read = \fread($fp, 4);
        if ($read === false) {
            return []; // @codeCoverageIgnore
        }
        $magicNumber = \unpack('N', $read)[1];

        $read = \fread($fp, 4);
        if ($read === false) {
            return []; // @codeCoverageIgnore
        }
        $numberOfImages = \unpack('N', $read)[1];

        if ($limit > 0) {
            $numberOfImages = \min($numberOfImages, $limit);
        }

        $read = \fread($fp, 4);
        if ($read === false) {
            return []; // @codeCoverageIgnore
        }
        $numberOfRows = \unpack('N', $read)[1];

        $read = \fread($fp, 4);
        if ($read === false) {
            return []; // @codeCoverageIgnore
        }
        $numberOfColumns = \unpack('N', $read)[1];

        $images = [];
        for ($i = 0; $i < $numberOfImages; ++$i) {
            $read = \fread($fp, $numberOfRows * $numberOfColumns);
            if ($read === false) {
                return []; // @codeCoverageIgnore
            }
            $images[] = \array_values(\unpack('C*', $read));
        }

        \fclose($fp);

        return $images;
    }

    /**
     * Read labels from from path
     *
     * @param string $path  Labels path
     * @param string $limit Limit
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function readLabels(string $path, int $limit = 0) : array
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $fp = \fopen($path, 'r');
        if ($fp === false) {
            throw new PathException($path); // @codeCoverageIgnore
        }

        $read = \fread($fp, 4);
        if ($read === false) {
            return []; // @codeCoverageIgnore
        }
        $magicNumber = \unpack('N', $read)[1];

        $read = \fread($fp, 4);
        if ($read === false) {
            return []; // @codeCoverageIgnore
        }
        $numberOfLabels = \unpack('N', $read)[1];

        if ($limit > 0) {
            $numberOfLabels = \min($numberOfLabels, $limit);
        }

        $labels = [];
        for ($i = 0; $i < $numberOfLabels; ++$i) {
            $read = \fread($fp, 1);
            if ($read === false) {
                return []; // @codeCoverageIgnore
            }
            $labels[] = \unpack('C', $read)[1];
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
            // @todo: consider to path the k-limit to the getDistances function for earlier filtering
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
                    'prob'  => $countedCandidates[$candidateLabels[$i]] / $k,
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
     * Categorize an unknown image
     *
     * @param string $path       Path to the image to categorize/evaluate/match against the training data
     * @param string $comparison Amount of comparisons
     * @param string $limit      Limit (0 = unlimited)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function match(string $path, int $comparison = 3, int $limit = 0) : array
    {
        // @todo: implement image reading if it isn't an mnist file
        $Xtest = $this->readImages($path, $limit);

        return $this->kNearest($this->Xtrain, $this->ytrain, $Xtest, $comparison);
    }
}
