<?php


use App\Payday;
use App\PaymentDayCalculator;
use App\Payroll;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;

final class PayrollTest extends TestCase
{
    private const EXPECTED_NUMBER_OF_MONTHS = 12;

    function testCalculate(): void
    {
        $baseSalaryCalculator = $this->createMock(PaymentDayCalculator::class);
        $baseSalaryCalculator->method('calculate')->willReturn(new DateTimeImmutable('2023-01-01'));

        $payroll = new Payroll(new MockClock('2023-05-01 00:00:00'), $baseSalaryCalculator);
        $paydays = $payroll->generate();

        $this->assertCount(self::EXPECTED_NUMBER_OF_MONTHS, $paydays);
        $this->assertContainsOnlyInstancesOf(Payday::class, $paydays);
    }
}
