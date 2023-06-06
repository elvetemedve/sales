<?php

namespace App;

use DateTimeImmutable;

final class Payday
{
    public function __construct(private DateTimeImmutable $paymentDate)
    {
    }

    public function getPaymentDate(): DateTimeImmutable
    {
        return $this->paymentDate;
    }
}