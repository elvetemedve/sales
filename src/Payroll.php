<?php

namespace App;

use ArrayIterator;
use Traversable;

class Payroll
{

    public function __construct(private Calendar $calendar, private PaymentDayCalculator $baseSalaryCalculator)
    {
    }

    public function generate(): Traversable
    {
        $paydays = [];
        foreach ($this->calendar->createOneMonthPeriod() as $month) {
            $paydays[] = new Payday($this->baseSalaryCalculator->calculate($month));
        }

        return new ArrayIterator($paydays);
    }
}