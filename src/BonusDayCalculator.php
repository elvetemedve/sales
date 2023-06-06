<?php

namespace App;

use DateTimeImmutable;

class BonusDayCalculator implements PaymentDayCalculator
{
    public function __construct(private Calendar $calendar)
    {
    }

    public function calculate(DateTimeImmutable $month): DateTimeImmutable
    {
        $bonusDay = $this->calendar->setDay($month, 15);

        if ($this->calendar->isWeekend($bonusDay)) {
            return $this->calendar->nextWeekday($bonusDay, DayOfWeek::Wednesday);
        }

        return $bonusDay;
    }
}