<?php

namespace App;

use App\Exception\IOException;
use App\Factory\ExporterFactory;

class Payroll
{

    public function __construct(
        private Calendar $calendar,
        private PaymentDayCalculator $baseSalaryCalculator,
        private PaymentDayCalculator $bonusCalculator,
        private ExporterFactory $csvExporterFactory
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

        $this->csvExporterFactory->createForPayday($paydays)->exportToFile($filename);
    }
}