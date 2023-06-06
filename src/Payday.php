<?php

namespace App;

use DateTimeImmutable;

final class Payday
{
    public function __construct(private DateTimeImmutable $paymentDate, private DateTimeImmutable $bonusDate)
    {
    }

    public function getSalaryDate(): DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function getBonusDate(): DateTimeImmutable
    {
        return $this->bonusDate;
    }
}