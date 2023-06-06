<?php

namespace App\Factory;

use App\Domain\Payday;
use App\IO\Exporter;

interface ExporterFactory
{
    /**
     * @param Payday[] $paydays
     */
    public function createForPayday(array $paydays): Exporter;
}