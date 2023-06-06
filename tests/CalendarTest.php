<?php


use App\Calendar;
use App\Weekday;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\Clock;

final class CalendarTest extends TestCase
{
    #[TestWith([new DateTimeImmutable('2023-01-01'), true])]
    #[TestWith([new DateTimeImmutable('2023-01-02'), false])]
    #[TestWith([new DateTimeImmutable('2023-02-04'), true])]
    #[TestWith([new DateTimeImmutable('2023-02-05'), true])]
    #[TestWith([new DateTimeImmutable('2023-02-06'), false])]
    function testIsWeekend(DateTimeImmutable $date, bool $isWeekend): void
    {
        $calendar = new Calendar(new Clock());

        $this->assertEquals($isWeekend, $calendar->isWeekend($date));
    }

    #[TestWith([new DateTimeImmutable('2023-01-01'), Weekday::Friday, new DateTimeImmutable('2023-01-06')])]
    #[TestWith([new DateTimeImmutable('2023-01-01'), Weekday::Sunday, new DateTimeImmutable('2023-01-08')])]
    function testNextWeekday(DateTimeImmutable $currentDate, Weekday $nextDay, DateTimeImmutable $expectedDate)
    {
        $calendar = new Calendar(new Clock());

        $this->assertEquals($expectedDate, $calendar->nextWeekday($currentDate, $nextDay));
    }
}
