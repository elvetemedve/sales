<?php

use App\Domain\Calculator\SalaryDayCalculator;
use App\Time\Calendar;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class SalaryDayCalculatorTest extends TestCase
{
    #[TestWith([new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-01-31'), new DateTimeImmutable('2023-01-31'), new DateTimeImmutable('2023-01-31'), false])]
    #[TestWith([new DateTimeImmutable('2023-04-01'), new DateTimeImmutable('2023-04-30'), new DateTimeImmutable('2023-04-28'), new DateTimeImmutable('2023-04-28'), true])]
    #[TestWith([new DateTimeImmutable('2023-03-11'), new DateTimeImmutable('2023-03-31'), new DateTimeImmutable('2023-03-31'), new DateTimeImmutable('2023-03-31'), false])]
    function testPaymentDayCalculation(
        DateTimeImmutable $month,
        DateTimeImmutable $lastDayOfMonth,
        DateTimeImmutable $lastWeekdayOfWeek,
        DateTimeImmutable $expectedDay,
        bool $isWeekend
    ): void {
        $calendar = $this->createStub(Calendar::class);
        $calendar->method('isWeekend')->willReturn($isWeekend);
        $calendar->method('lastDayOfMonth')->willReturn($lastDayOfMonth);
        $calendar->method('lastWeekdayOfWeek')->willReturn($lastWeekdayOfWeek);

        $calculator = new SalaryDayCalculator($calendar);
        $day = $calculator->calculate($month);

        $this->assertEquals($expectedDay, $day);
    }
}
