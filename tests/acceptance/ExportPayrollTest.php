<?php

namespace App\Tests\acceptance;

use App\Domain\Calculator\BonusDayCalculator;
use App\Domain\Calculator\SalaryDayCalculator;
use App\Domain\Payroll;
use App\Factory\CsvExporterFactory;
use App\Time\Calendar;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Filesystem\Filesystem;

final class ExportPayrollTest extends TestCase
{
    private const EXPECTED_CSV_CONTENT = <<<TEXT
Month,"Salary Date","Bonus Date"
February,2023-02-28,2023-02-15
March,2023-03-31,2023-03-15
April,2023-04-28,2023-04-19
May,2023-05-31,2023-05-15
June,2023-06-30,2023-06-15
July,2023-07-31,2023-07-19
August,2023-08-31,2023-08-15
September,2023-09-29,2023-09-15
October,2023-10-31,2023-10-18
November,2023-11-30,2023-11-15
December,2023-12-29,2023-12-15
January,2024-01-31,2024-01-15

TEXT;

    function testExportPayroll()
    {
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->expects($this->once())->method('dumpFile')->with($this->identicalTo('testfile'), $this->callback(function ($stream) {
            $this->assertEquals(self::EXPECTED_CSV_CONTENT, stream_get_contents($stream));

            return true;
        }));


        $calendar = new Calendar(new MockClock('2023-02-02'));
        $payroll = new Payroll(
            $calendar,
            new SalaryDayCalculator($calendar),
            new BonusDayCalculator($calendar),
            new CsvExporterFactory($filesystem)
        );

        $payroll->generate('testfile');
    }
}