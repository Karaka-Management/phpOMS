<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Parser\Calendar
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Calendar;

/**
 * iCal parser.
 *
 * @package phpOMS\Utils\Parser\Calendar
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ICalParser
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Parse iCal data
     *
     * @param string $data iCal data
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function parse(string $data) : array
    {
        \preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $data, $matches, \PREG_SET_ORDER);

        $eventList = [];

        foreach ($matches as $match) {
            $event = [];

            \preg_match('/UID:(.*?)\n/', $match[1], $uidMatch);
            $event['uid'] = $uidMatch[1] ?? null;

            \preg_match('/STATUS:(.*?)\n/', $match[1], $statusMatch);
            $event['status'] = $statusMatch[1] ?? null;

            \preg_match('/DTSTART:(.*?)\n/', $match[1], $startMatch);
            $event['start'] = $startMatch[1] ?? null;

            \preg_match('/DTEND:(.*?)\n/', $match[1], $endMatch);
            $event['end'] = $endMatch[1] ?? null;

            \preg_match('/ORGANIZER:(.*?)\n/', $match[1], $organizerMatch);
            $event['organizer'] = $organizerMatch[1] ?? null;

            \preg_match('/SUMMARY:(.*?)\n/', $match[1], $summaryMatch);
            $event['summary'] = $summaryMatch[1] ?? null;

            \preg_match('/DESCRIPTION:(.*?)\n/', $match[1], $descriptionMatch);
            $event['description'] = $descriptionMatch[1] ?? null;

            \preg_match('/LOCATION:(.*?)\n/', $match[1], $locationMatch);
            $event['location'] = $locationMatch[1] ?? null;

            \preg_match('/GEO:(.*?)\n/', $match[1], $geo);
            $temp         = \explode(';', $geo[1]);
            $event['geo'] = [
                'lat' => (float) \trim($temp[0] ?? '0'),
                'lon' => (float) \trim($temp[1] ?? '0'),
            ];

            \preg_match('/URL:(.*?)\n/', $match[1], $url);
            $event['url'] = $url[1] ?? null;

            // Check if this event is recurring
            if (\preg_match('/RRULE:(.*?)\n/', $match[1], $rruleMatch)) {
                $rrule = self::parseRRule($rruleMatch[1]);
                $event = \array_merge($event, $rrule);
            }

            $eventList[] = $event;
        }

        return $eventList;
    }

    /**
     * Parse rrule
     *
     * @param string $rruleString rrule string
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function parseRRule($rruleString) : array
    {
        $rrule = [];

        \preg_match('/FREQ=(.*?);/', $rruleString, $freqMatch);
        $rrule['freq'] = $freqMatch[1] ?? null;

        \preg_match('/INTERVAL=(.*?);/', $rruleString, $intervalMatch);
        $rrule['interval'] = $intervalMatch[1] ?? null;

        \preg_match('/BYMONTH=(.*?);/', $rruleString, $monthMatch);
        $rrule['bymonth'] = $monthMatch[1] ?? null;

        \preg_match('/BYMONTHDAY=(.*?);/', $rruleString, $monthdayMatch);
        $rrule['bymonthday'] = $monthdayMatch[1] ?? null;

        $rrule['count'] = \preg_match('/COUNT=(.*?);/', $rruleString, $countMatch)
            ? (int) ($countMatch[1] ?? 0)
            : null;

        $rrule['until'] = \preg_match('/UNTIL=(.*?);/', $rruleString, $untilMatch)
            ? $untilMatch[1] ?? null
            : null;

        return $rrule;
    }
}
