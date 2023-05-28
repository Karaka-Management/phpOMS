<?php
/**
 * Karaka
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

            \preg_match('/UID:(.*?)\r\n/', $match[1], $uidMatch);
            $event['uid'] = \DateTime::createFromFormat('Ymd\THis', $uidMatch[1]);

            \preg_match('/STATUS:(.*?)\r\n/', $match[1], $statusMatch);
            $event['status'] = \DateTime::createFromFormat('Ymd\THis', $statusMatch[1]);

            \preg_match('/DTSTART:(.*?)\r\n/', $match[1], $startMatch);
            $event['start'] = \DateTime::createFromFormat('Ymd\THis', $startMatch[1]);

            \preg_match('/DTEND:(.*?)\r\n/', $match[1], $endMatch);
            $event['end'] = \DateTime::createFromFormat('Ymd\THis', $endMatch[1]);

            \preg_match('/ORGANIZER:(.*?)\r\n/', $match[1], $organizerMatch);
            $event['organizer'] = \DateTime::createFromFormat('Ymd\THis', $organizerMatch[1]);

            \preg_match('/SUMMARY:(.*?)\r\n/', $match[1], $summaryMatch);
            $event['summary'] = $summaryMatch[1];

            \preg_match('/DESCRIPTION:(.*?)\r\n/', $match[1], $descriptionMatch);
            $event['description'] = $descriptionMatch[1];

            \preg_match('/LOCATION:(.*?)\r\n/', $match[1], $locationMatch);
            $event['location'] = $locationMatch[1];

            // Check if this event is recurring
            if (\preg_match('/RRULE:(.*?)\r\n/', $match[1], $rruleMatch)) {
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
        $rrule['freq'] = $freqMatch[1];

        \preg_match('/INTERVAL=(.*?);/', $rruleString, $intervalMatch);
        $rrule['interval'] = (int) $intervalMatch[1];

        if (\preg_match('/COUNT=(.*?);/', $rruleString, $countMatch)) {
            $rrule['count'] = (int) $countMatch[1];
        }

        if (\preg_match('/UNTIL=(.*?);/', $rruleString, $untilMatch)) {
            $rrule['until'] = \DateTime::createFromFormat('Ymd\THis', $untilMatch[1]);
        }

        return $rrule;
    }
}
