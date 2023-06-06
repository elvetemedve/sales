<?php

namespace App\Domain\Calculator;

use App\Time\Calendar;
use DateTimeImmutable;

class SalaryDayCalculator implements PaymentDayCalculator
{
    public function __construct(private Calendar $calendar)
    {
    }

    public function calculate(DateTimeImmutable $month): DateTimeImmutable
    {
        $lastDayOfMonth = $this->calendar->lastDayOfMonth($month);

        if ($this->calendar->isWeekend($lastDayOfMonth)) {
            return $this->calendar->lastWeekdayOfWeek($lastDayOfMonth);
        }

        return $lastDayOfMonth;
    }
}