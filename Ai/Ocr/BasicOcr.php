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
 * Matching a value with a set of coins
 *
 * @package phpOMS\Ai\Ocr
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class BasicOcr
{
    private array $Xtrain = [];

    private array $ytrain = [];

    public function trainWith(string $dataPath, string $labelPath) : void
    {
        $Xtrain = $this->readImages($dataPath);
        $ytrain = $this->readLabels($labelPath);

        $this->Xtrain = \array_merge($this->Xtrain, $this->extractFeatures($Xtrain));
        $this->ytrain = \array_merge($this->ytrain, $this->extractFeatures($ytrain));
    }

    private function readImages(string $path) : array
    {
        $fp = \fopen($path, 'r');
        $magicNumber = \unpack('l', \fread($fp, 4))[1];
        $numberOfImages = \unpack('l', \fread($fp, 4))[1];
        $numberOfRows = \unpack('l', \fread($fp, 4))[1];
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

    private function readLabels(string $path) : array
    {
        $fp = \fopen($path, 'r');
        $magicNumber = \unpack('l', \fread($fp, 4))[1];
        $numberOfLabels = \unpack('l', \fread($fp, 4))[1];

        $labels = [];

        for ($i = 0; $i < $numberOfLabels; ++$i) {
            $labels[] = \unpack('l', \fread($fp, 1))[1]; //fread($fp, 1);
        }

        return $labels;
    }

    public function flatten(array $data) : array
    {
        $flat = [];
        foreach ($data as $sublist) {
            foreach ($sublist as $pixel) {
                $flat[] = $pixel;
            }
        }

        return $flat;
    }

    private function extractFeatures(array $data) : array
    {
        $features = [];
        foreach ($data as $sample) {
            $features[] = $this->flatten($sample);
        }

        return $features;
    }

    private function kNearest(array $Xtrain, array $ytrain, array $Xtest, int $k = 3) : array
    {
        $predictedLabels = [];
        foreach ($Xtest as $sample) {
            // @todo: consider to path the k-limit to the getDistances function for earlier filtering
            $distances = $this->getDistances($Xtrain, $sample);
            \asort($distances);

            // find possible k-labels for a image
            $kKeys = \array_keys(\array_slice($distances, 0, $k));
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

    private function getDistances(array $Xtrain, $sample) : array
    {
        $dist = [];
        foreach ($Xtrain as $train) {
            $dist[] = MetricsND::euclidean($train, $sample);
        }

        return $dist;
    }

    public function match(string $path) : array
    {
        // @todo: implement image reading if it isn't an mnist file
        $Xtest = $this->readImages($path);

        return $this->kNearest($this->Xtrain, $this->ytrain, $Xtest);
    }
}
