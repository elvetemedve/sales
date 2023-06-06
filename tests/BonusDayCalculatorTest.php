<?php


use App\BonusDayCalculator;
use App\Calendar;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class BonusDayCalculatorTest extends TestCase
{
    #[TestWith([new DateTimeImmutable('2023-02-01'), new DateTimeImmutable('2023-02-15'), false])]
    #[TestWith([new DateTimeImmutable('2023-04-01'), new DateTimeImmutable('2023-04-19'), true])]
    #[TestWith([new DateTimeImmutable('2023-05-07'), new DateTimeImmutable('2023-05-15'), false])]
    function testPaymentDayCalculation(DateTimeImmutable $month, DateTimeImmutable $expectedDay, bool $isWeekend): void
    {
        $calendar = $this->createStub(Calendar::class);
        $calendar->method('isWeekend')->willReturn($isWeekend);
        $calendar->method('nextWeekday')->willReturn(new DateTimeImmutable('2023-04-19'));

        $calculator = new BonusDayCalculator($calendar);
        $day = $calculator->calculate($month);

        $this->assertEquals($expectedDay, $day);
    }
}
