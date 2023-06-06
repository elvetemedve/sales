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
        // TODO move it to Calendar::setDay() method
        $bonusDay = $month->setDate($month->format('Y'), $month->format('m'), 15);

        if ($this->calendar->isWeekend($bonusDay)) {
            return $this->calendar->nextWeekday($bonusDay, Weekday::Wednesday);
        }

        return $bonusDay;
    }
}