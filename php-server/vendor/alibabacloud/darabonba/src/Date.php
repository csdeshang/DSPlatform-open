<?php

namespace AlibabaCloud\Dara;

use DateTime;
use DateTimeZone;
use DateInterval;
use AlibabaCloud\Dara\Exception\DaraException;

class Date
{
    private $date = null;

    public function __construct($date = 'now') {
        $pattern = '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}(?:\.\d+)?)(?: \+?(\d{4}))?/';
        if (ctype_digit($date) || is_numeric($date)) {
            $this->date = (new DateTime())->setTimestamp($date);
        } elseif (preg_match($pattern, $date, $matches)) {
            $timeStr = $matches[1];
            $tzStr = isset($matches[2]) ? $matches[2] : null;

            if ($tzStr) {
                $timezone = new DateTimeZone($this->convertTzOffsetToTzString($tzStr));
                $this->date = DateTime::createFromFormat('Y-m-d H:i:s.u', $timeStr, $timezone);
            } else {
                $this->date = new DateTime($timeStr);
            }
        } else {
            $this->date = new DateTime($date);
        }
        if($this->date === false || is_null($this->date)) {
            throw new DaraException([], $date . ' is not a valid time str.');
        }
    }

    private function convertTzOffsetToTzString($offset) {
        $sign = (intval($offset) >= 0) ? '+' : '-';
        $hours = substr($offset, 0, 2);
        $minutes = substr($offset, 2, 2);
        return $sign . $hours . ':' . $minutes;
    }

    public function format($layout) {
        $layout = strtr($layout, [
            'yyyy' => 'Y', 'yy' => 'y',
            'MM' => 'm', 'M' => 'n',
            'DD' => 'd', 'D' => 'j',
            'HH' => 'H', 'H' => 'G',
            'hh' => 'h', 'h' => 'g',
            'mm' => 'i', 'm' => 'i',
            'ss' => 's', 's' => 's',
            'A' => 'A', 'a' => 'a',
            'E' => 'N', 'YYYY' => 'Y',
        ]);
        return $this->date->format($layout);
    }

    public function UTC($time = null)
    {
        $utcDate = clone $this->date;
        $utcDate->setTimezone(new DateTimeZone('UTC'));
        return $utcDate->format('Y-m-d H:i:s.u O \\U\\T\\C');
    }

    public function unix() {
        $date = $this->date;
        return $date->getTimestamp();
    }

    public function sub($unit, $amount) {
        $interval = new DateInterval('P' . strtoupper($amount) . strtoupper((string)$unit));
        $this->date->sub($interval);
        return $this;
    }

    public function add($unit, $amount) {
        $interval = new DateInterval('P' . strtoupper($amount) . strtoupper((string)$unit));
        $this->date->add($interval);
        return $this;
    }

    public function diff($diffDate, $unit = null) {
        $interval = $this->date->diff($diffDate->getDateObject());
        switch ($unit) {
            case 'year':
                return $interval->y;
            case 'month':
                return $interval->m;
            case 'day':
                return $interval->d;
            case 'hour':
                return $interval->h;
            case 'minute':
                return $interval->i;
            case 'second':
                return $interval->s;
            default:
                return ($interval->days * 24 * 60 * 60) + 
                       ($interval->h * 60 * 60) + 
                       ($interval->i * 60) + 
                       $interval->s;
        }
    }

    public function hour() {
        return (int)$this->date->format('H');
    }

    public function minute() {
        return (int)$this->date->format('i');
    }

    public function second() {
        return (int)$this->date->format('s');
    }

    public function month() {
        return (int)$this->date->format('n');
    }

    public function year() {
        return (int)$this->date->format('Y');
    }

    public function dayOfMonth() {
        return (int)$this->date->format('j');
    }

    public function dayOfWeek() {
        $weekday = (int)$this->date->format('w');
        return $weekday === 0 ? 7 : $weekday;
    }

    public function weekOfYear() {
        $week = (int)$this->date->format('W');
        $weekday = (int)$this->date->format('w');
    
        if ($weekday === 0 && $this->date->format('z') === (string)($this->date->format('L') ? '365' : '364')) {
            return (int)$this->date->sub(new DateInterval('P1D'))->format('W');
        }
    
        return $week;
    }

    public function getDateObject() {
        return clone $this->date;
    }
}
