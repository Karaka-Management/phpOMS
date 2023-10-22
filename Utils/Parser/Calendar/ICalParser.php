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
class ICalParser
{
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
            $event['uid'] = \DateTime::createFromFormat('Ymd\THis', $uidMatch[1] ?? '');

            \preg_match('/STATUS:(.*?)\n/', $match[1], $statusMatch);
            $event['status'] = \DateTime::createFromFormat('Ymd\THis', $statusMatch[1] ?? '');

            \preg_match('/DTSTART:(.*?)\n/', $match[1], $startMatch);
            $event['start'] = \DateTime::createFromFormat('Ymd\THis', $startMatch[1] ?? '');

            \preg_match('/DTEND:(.*?)\n/', $match[1], $endMatch);
            $event['end'] = \DateTime::createFromFormat('Ymd\THis', $endMatch[1] ?? '');

            \preg_match('/ORGANIZER:(.*?)\n/', $match[1], $organizerMatch);
            $event['organizer'] = $organizerMatch[1] ?? '';

            \preg_match('/SUMMARY:(.*?)\n/', $match[1], $summaryMatch);
            $event['summary'] = $summaryMatch[1] ?? '';

            \preg_match('/DESCRIPTION:(.*?)\n/', $match[1], $descriptionMatch);
            $event['description'] = $descriptionMatch[1] ?? '';

            \preg_match('/LOCATION:(.*?)\n/', $match[1], $locationMatch);
            $event['location'] = $locationMatch[1] ?? '';

            \preg_match('/GEO:(.*?)\n/', $match[1], $geo);
            $temp         = \explode(';', $geo[1]);
            $event['geo'] = [
                'lat' => (float) $temp[0],
                'lon' => (float) $temp[1],
            ];

            \preg_match('/URL:(.*?)\n/', $match[1], $url);
            $event['url'] = $url[1] ?? '';

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
        $rrule['freq'] = $freqMatch[1] ?? '';

        \preg_match('/INTERVAL=(.*?);/', $rruleString, $intervalMatch);
        $rrule['interval'] = (int) ($intervalMatch[1] ?? 0);

        if (\preg_match('/COUNT=(.*?);/', $rruleString, $countMatch)) {
            $rrule['count'] = (int) ($countMatch[1] ?? 0);
        }

        if (\preg_match('/UNTIL=(.*?);/', $rruleString, $untilMatch)) {
            $rrule['until'] = \DateTime::createFromFormat('Ymd\THis', $untilMatch[1] ?? '');
        }

        return $rrule;
    }
}
