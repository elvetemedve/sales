<?php

namespace App\Factory;

use App\IO\Exporter;
use App\Payday;

interface ExporterFactory
{
    /**
     * @param Payday[] $paydays
     */
    public function createForPayday(array $paydays): Exporter;
}