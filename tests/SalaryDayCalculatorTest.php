<?php

use App\Calendar;
use App\SalaryDayCalculator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

final class SalaryDayCalculatorTest extends TestCase
{
    #[TestWith([new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-01-31'), false])]
    #[TestWith([new DateTimeImmutable('2023-04-01'), new DateTimeImmutable('2023-04-28'), true])]
    #[TestWith([new DateTimeImmutable('2023-03-11'), new DateTimeImmutable('2023-03-31'), false])]
    function testPaymentDayCalculation(DateTimeImmutable $month, DateTimeImmutable $expectedDay, bool $isWeekend): void
    {
        $calendar = $this->createStub(Calendar::class);
        $calendar->method('isWeekend')->willReturn($isWeekend);

        $calculator = new SalaryDayCalculator($calendar);
        $day = $calculator->calculate($month);

        $this->assertEquals($expectedDay, $day);
    }
}
