<?php

namespace App\Domain;

use App\Domain\Calculator\PaymentDayCalculator;
use App\Exception\IOException;
use App\Factory\ExporterFactory;
use App\Time\Calendar;

class Payroll
{

    public function __construct(
        private Calendar $calendar,
        private PaymentDayCalculator $baseSalaryCalculator,
        private PaymentDayCalculator $bonusCalculator,
        private ExporterFactory $exporterFactory
    )
    {
    }

    /**
     * @throws IOException
     */
    public function generate(string $filename): void
    {
        $paydays = [];

        foreach ($this->calendar->createOneMonthPeriod() as $month) {
            $paydays[] = new Payday($this->baseSalaryCalculator->calculate($month), $this->bonusCalculator->calculate($month));
        }

        $this->exporterFactory->createForPayday($paydays)->exportToFile($filename);
    }
}