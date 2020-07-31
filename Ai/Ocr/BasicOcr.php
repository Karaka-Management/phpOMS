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
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function trainWith(string $dataPath, string $labelPath) : void
    {
        $Xtrain = $this->readImages($dataPath);
        $ytrain = $this->readLabels($labelPath);

        $this->Xtrain = \array_merge($this->Xtrain, $this->extractFeatures($Xtrain));
        $this->ytrain = \array_merge($this->ytrain, $this->extractFeatures($ytrain));
    }

    /**
     * Reat image from path
     *
     * @param string $path Image to read
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function readImages(string $path) : array
    {
        $fp = \fopen($path, 'r');
        if ($fp === false) {
            throw new \Exception();
        }

        $magicNumber     = \unpack('l', \fread($fp, 4))[1];
        $numberOfImages  = \unpack('l', \fread($fp, 4))[1];
        $numberOfRows    = \unpack('l', \fread($fp, 4))[1];
        $numberOfColumns = \unpack('l', \fread($fp, 4))[1];

        $images = [];

        for ($i = 0; $i < $numberOfImages; ++$i) {
            $image = [];

            for ($row = 0; $row < $numberOfRows; ++$row) {
                $rows = [];

                for ($col = 0; $col < $numberOfColumns; ++$col) {
                    $rows[] = \unpack('l', \fread($fp, 1))[1]; //fread($fp, 1);
                }

                $image[] = $rows;
            }

            $images[] = $image;
        }

        return $images;
    }

    /**
     * Read labels from from path
     *
     * @param string $path Labels path
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function readLabels(string $path) : array
    {
        $fp = \fopen($path, 'r');
        if ($fp === false) {
            throw new \Exception();
        }

        $magicNumber    = \unpack('l', \fread($fp, 4))[1];
        $numberOfLabels = \unpack('l', \fread($fp, 4))[1];

        $labels = [];

        for ($i = 0; $i < $numberOfLabels; ++$i) {
            $labels[] = \unpack('l', \fread($fp, 1))[1]; //fread($fp, 1);
        }

        return $labels;
    }

    /**
     * Extract data and labe information from image data
     *
     * @param array $data Image data and label information from the images
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function extractFeatures(array $data) : array
    {
        $features = [];
        foreach ($data as $sample) {
            $features[] = $this->flatten($sample);
        }

        return $features;
    }

    /**
     * Reduce the dimension of the data and label information
     *
     * @param array $data Image data and labell information to flatten
     *
     * @return arry
     *
     * @sicne 1.0.0
     */
    private function flatten(array $data) : array
    {
        $flat = [];
        foreach ($data as $sublist) {
            foreach ($sublist as $pixel) {
                $flat[] = $pixel;
            }
        }

        return $flat;
    }

    /**
     * Find the k-nearest matches for test data
     *
     * @param array $Xtrain Image data used for training
     * @param array $ytrain Labels associated with the trained data
     * @param array $Xtrain Image data from the image to categorize
     * @param int   $k      Amount of best fits that should be found
     */
    private function kNearest(array $Xtrain, array $ytrain, array $Xtest, int $k = 3) : array
    {
        $predictedLabels = [];
        foreach ($Xtest as $sample) {
            // @todo: consider to path the k-limit to the getDistances function for earlier filtering
            $distances = $this->getDistances($Xtrain, $sample);
            \asort($distances);

            // find possible k-labels for a image
            $kKeys           = \array_keys(\array_slice($distances, 0, $k));
            $candidateLabels = [];

            foreach ($kKeys as $key) {
                $candidateLabels[] = $ytrain[$key];
            }

            // find best match
            $countedCandidates = \array_count_values($candidateLabels);
            \asort($countedCandidates);

            $predictedLabels[] = ['label' => \array_key_first($countedCandidates), 'prob' => \reset($countedCandidates) / $k];
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
     * @param string $path Path to the image to categorize/evaluate/match against the training data
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function match(string $path) : array
    {
        // @todo: implement image reading if it isn't an mnist file
        $Xtest = $this->readImages($path);

        return $this->kNearest($this->Xtrain, $this->ytrain, $Xtest);
    }
}
