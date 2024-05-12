<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Path exception class.
 *
 * @package phpOMS\System\File
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SearchUtils
{
    public static function findInFile(string $path, array $keywords, int $distance = 500) : array
    {
        $fp = \fopen($path, "r");
        if ($fp === false) {
            return [];
        }

        $positions = [];

        $globalPos = 0;

        while (!\feof($fp)) {
            $line = \fgets($fp);

            foreach ($keywords as $keyword) {
                $pos = \stripos($line, $keyword);
                if ($pos === false) {
                    continue;
                }

                while ($pos !== false) {
                    $positions[$keyword][] = $globalPos + $pos;

                    $pos = \stripos($line, $keyword, $pos + 1);
                }
            }

            $globalPos += \strlen($line);
        }

        \fclose($fp);

        if (empty($positions) || \count($keywords) !== \count($positions)) {
            return [];
        }

        $start = \reset($keywords);
        $distances = [];

        foreach ($positions[$start] as $pos) {
            if ($pos < 0) {
                continue;
            }

            $distance = [
                'start' => $pos,
                'end' => $pos,
                'distance' => 0,
            ];

            foreach ($positions as $keyword => $found) {
                $closestStart = null;
                $closestEnd = null;
                $inBetween = null;

                foreach ($found as $pos2) {
                    if ($pos2 < 0) {
                        continue 2;
                    }

                    if ($pos2 >= $distance['start'] && $pos2 <= $distance['end']) {
                        $inBetween = $pos2;

                        break;
                    }

                    if ($closestStart === null
                        || \abs($pos2 - $distance['start']) < \abs($closestStart - $distance['start'])
                    ) {
                        $closestStart = $pos2;
                    }

                    if ($closestEnd === null
                        || \abs($pos2 - $distance['end']) < \abs($closestEnd - $distance['end'])
                    ) {
                        $closestEnd = $pos2;
                    }
                }

                // The following is only perfect for inBetween
                // For the other cases there could be a scenario where the closer value is actually bad
                // because for the next keyword the farther one would be inBetween
                if ($inBetween !== null) {
                    continue; // Perfect
                } elseif ($closestStart < $distance['start']
                    && (\abs($closestStart - $distance['start']) <= \abs($closestEnd - $distance['end']) || $closestEnd > $distance['end'])) {
                    $distance['start'] = \min($distance['start'], $closestStart);
                } else {
                    $distance['end'] = \max($distance['end'], $closestEnd);
                }
            }

            $distance['distance'] = $distance['end'] - $distance['start'];
            $distances[] = $distance;
        }

        if (empty($distances)) {
            return [];
        }

        \uasort($distances, function (array $a, array $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return $distances;
    }

    public static function getTextExtract(string $path, int $pos, string $start, string $end) : string
    {
        $fp = \fopen($path, "r");
        if ($fp === false) {
            return [];
        }

        $startPos = -1;
        while (($line = \fgets($fp)) !== false && \ftell($fp) < $pos) {
            if (\stripos($line, $start) !== false) {
                $startPos = \ftell($fp);
            }
        }

        \fseek($fp, $pos);

        $endPos = -1;
        while (($line = \fgets($fp)) !== false) {
            if (\stripos($line, $end) !== false) {
                $endPos = \ftell($fp);
                break;
            }
        }

        if ($startPos < 0 || $endPos < 0) {
            \fclose($fp);
            return '';
        }

        \fseek($fp, $startPos);
        $extract = \fread($fp, $endPos - $startPos);

        \fclose($fp);

        return $extract;
    }
}
