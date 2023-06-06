<?php

namespace App;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class Calendar
{
    private const DAY_OF_WEEK_SATURDAY = 6;

    public function __construct(private ClockInterface $clock)
    {
    }

    public function isWeekend(DateTimeImmutable $lastDayOfMonth): bool
    {
        return $lastDayOfMonth->format('N') >= self::DAY_OF_WEEK_SATURDAY;
    }

    public function nextWeekday(DateTimeImmutable $date, Weekday $weekday): DateTimeImmutable
    {
        return $date->modify("next $weekday->name");
    }

}