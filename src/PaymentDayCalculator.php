<?php

namespace App;

use DateTimeImmutable;

interface PaymentDayCalculator
{
    public function calculate(DateTimeImmutable $month): DateTimeImmutable;
}