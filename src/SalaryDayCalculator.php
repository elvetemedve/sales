<?php

namespace App;

use DateInterval;
use DateTimeImmutable;

class SalaryDayCalculator implements PaymentDayCalculator
{
    private const DAY_OF_WEEK_FRIDAY = 5;

    public function __construct(private Calendar $calendar)
    {
    }

    public function calculate(DateTimeImmutable $month): DateTimeImmutable
    {
        // TODO move it to Calendar::lastDayOfMonth() method
        $lastDayOfMonth = $month
            ->setDate($month->format('Y'), $month->format('m'), 1)
            ->modify('+1 month')
            ->modify('-1 day');

        if ($this->calendar->isWeekend($lastDayOfMonth)) {
            return $this->findLastWeekday($lastDayOfMonth);
        }

        return $lastDayOfMonth;
    }

    // TODO move it to Calendar::lastWeekday() method
    private function findLastWeekday(DateTimeImmutable $lastDayOfMonth): DateTimeImmutable
    {
        $difference = $lastDayOfMonth->format('N') - self::DAY_OF_WEEK_FRIDAY;
        $interval = DateInterval::createFromDateString("$difference day");

        return $lastDayOfMonth->sub($interval);
    }
}