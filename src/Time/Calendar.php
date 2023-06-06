<?php

namespace App\Time;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class Calendar
{
    private const DAY_OF_WEEK_SATURDAY = 6;
    private const DAY_OF_WEEK_FRIDAY = 5;

    public function __construct(private ClockInterface $clock)
    {
    }

    public function isWeekend(DateTimeImmutable $lastDayOfMonth): bool
    {
        return $lastDayOfMonth->format('N') >= self::DAY_OF_WEEK_SATURDAY;
    }

    public function nextWeekday(DateTimeImmutable $date, DayOfWeek $weekday): DateTimeImmutable
    {
        return $date->modify("next $weekday->name");
    }

    public function setDay(DateTimeImmutable $date, int $day): DateTimeImmutable
    {
        return $date->setDate($date->format('Y'), $date->format('m'), $day);
    }

    public function lastDayOfMonth(DateTimeImmutable $date): DateTimeImmutable
    {
        return $this->setDay($date, 1)
            ->modify('+1 month')
            ->modify('-1 day');
    }

    public function lastWeekdayOfWeek(DateTimeImmutable $date): DateTimeImmutable
    {
        $difference = $date->format('N') - self::DAY_OF_WEEK_FRIDAY;
        $interval = DateInterval::createFromDateString("$difference day");

        return $date->sub($interval);
    }

    public function createOneMonthPeriod(): DatePeriod
    {
        $start = $this->clock->now();
        $end = $start->modify('+1 year');
        $interval = DateInterval::createFromDateString('1 month');

        return new DatePeriod($start, $interval, $end);
    }

}