<?php

namespace App;

use ArrayIterator;
use DateInterval;
use DatePeriod;
use Psr\Clock\ClockInterface;
use Traversable;

class Payroll
{

    public function __construct(private ClockInterface $clock, private PaymentDayCalculator $baseSalaryCalculator)
    {
    }

    public function generate(): Traversable
    {
        $start = $this->clock->now();
        $end = $start->modify('+1 year');
        $interval = DateInterval::createFromDateString('1 month');

        $paydays = [];
        // TODO move creating the DatePeriod to Calendar class
        foreach (new DatePeriod($start, $interval, $end) as $month) {
            $paydays[] = new Payday($this->baseSalaryCalculator->calculate($month));
        }

        return new ArrayIterator($paydays);
    }
}