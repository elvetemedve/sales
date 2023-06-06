<?php

namespace App\Domain\Calculator;

use DateTimeImmutable;

interface PaymentDayCalculator
{
    public function calculate(DateTimeImmutable $month): DateTimeImmutable;
}