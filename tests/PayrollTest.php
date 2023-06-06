<?php


use App\Domain\Calculator\BonusDayCalculator;
use App\Domain\Calculator\PaymentDayCalculator;
use App\Domain\Payday;
use App\Domain\Payroll;
use App\Factory\ExporterFactory;
use App\IO\Exporter;
use App\Time\Calendar;
use PHPUnit\Framework\TestCase;

final class PayrollTest extends TestCase
{
    function testCalculate(): void
    {
        $expectedPaymentDates = [
            new DateTimeImmutable('2023-01-01'),
            new DateTimeImmutable('2023-02-01'),
            new DateTimeImmutable('2023-03-01'),
        ];

        $expectedBonusDates = [
            new DateTimeImmutable('2023-01-02'),
            new DateTimeImmutable('2023-02-02'),
            new DateTimeImmutable('2023-03-02'),
        ];

        $baseSalaryCalculator = $this->createMock(PaymentDayCalculator::class);
        $baseSalaryCalculator->method('calculate')->willReturnOnConsecutiveCalls(...$expectedPaymentDates);

        $bonusCalculator = $this->createMock(BonusDayCalculator::class);
        $bonusCalculator->method('calculate')->willReturnOnConsecutiveCalls(...$expectedBonusDates);

        $csvExporter = $this->createMock(Exporter::class);
        $csvExporter->expects($this->once())->method('exportToFile')->with($this->identicalTo('testfile'));

        $csvExporterFactory = $this->createMock(ExporterFactory::class);
        $csvExporterFactory->expects($this->once())->method('createForPayday')->with($this->callback(function($paydays) use($expectedPaymentDates, $expectedBonusDates) {
            foreach ($paydays as $payday) {
                $this->assertEquals(new Payday(current($expectedPaymentDates), current($expectedBonusDates)), $payday);
                next($expectedPaymentDates);
                next($expectedBonusDates);
            }

            return true;
        }))->willReturn($csvExporter);

        $calendar = $this->createMock(Calendar::class);
        $calendar
            ->method('createOneMonthPeriod')
            ->willReturn(new DatePeriod(
                new DateTimeImmutable('2023-05-01'),
                DateInterval::createFromDateString('1 month'),
                new DateTimeImmutable('2023-08-01'),
            ));

        $payroll = new Payroll($calendar, $baseSalaryCalculator, $bonusCalculator, $csvExporterFactory);
        $payroll->generate('testfile');
    }
}
