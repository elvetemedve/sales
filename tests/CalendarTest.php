<?php


use App\Calendar;
use App\DayOfWeek;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;

final class CalendarTest extends TestCase
{
    private Calendar $calendar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calendar = new Calendar(new Clock());
    }

    #[TestWith([new DateTimeImmutable('2023-01-01'), true])]
    #[TestWith([new DateTimeImmutable('2023-01-02'), false])]
    #[TestWith([new DateTimeImmutable('2023-02-04'), true])]
    #[TestWith([new DateTimeImmutable('2023-02-05'), true])]
    #[TestWith([new DateTimeImmutable('2023-02-06'), false])]
    function testIsWeekend(DateTimeImmutable $date, bool $isWeekend): void
    {
        $this->assertEquals($isWeekend, $this->calendar->isWeekend($date));
    }

    #[TestWith([new DateTimeImmutable('2023-01-01'), DayOfWeek::Friday, new DateTimeImmutable('2023-01-06')])]
    #[TestWith([new DateTimeImmutable('2023-01-01'), DayOfWeek::Sunday, new DateTimeImmutable('2023-01-08')])]
    function testNextWeekday(DateTimeImmutable $currentDate, DayOfWeek $nextDay, DateTimeImmutable $expectedDate)
    {
        $this->assertEquals($expectedDate, $this->calendar->nextWeekday($currentDate, $nextDay));
    }

    #[TestWith([new DateTimeImmutable('2023-01-01'), 11, new DateTimeImmutable('2023-01-11')])]
    #[TestWith([new DateTimeImmutable('2023-02-22'), 11, new DateTimeImmutable('2023-02-11')])]
    function testSetDay(DateTimeImmutable $currentDate, int $day, DateTimeImmutable $expectedDate)
    {
        $this->assertEquals($expectedDate, $this->calendar->setDay($currentDate, $day));
    }


    #[TestWith([new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-01-31')])]
    #[TestWith([new DateTimeImmutable('2023-02-01'), new DateTimeImmutable('2023-02-28')])]
    #[TestWith([new DateTimeImmutable('2023-04-01'), new DateTimeImmutable('2023-04-30')])]
    function testLastDayOfMonth(DateTimeImmutable $currentDate, DateTimeImmutable $expectedDate)
    {
        $this->assertEquals($expectedDate, $this->calendar->lastDayOfMonth($currentDate));
    }

    #[TestWith([new DateTimeImmutable('2023-01-04'), new DateTimeImmutable('2023-01-06')])]
    #[TestWith([new DateTimeImmutable('2023-01-08'), new DateTimeImmutable('2023-01-06')])]
    function testLastWeekday(DateTimeImmutable $currentDate, DateTimeImmutable $expectedDate)
    {
        $this->assertEquals($expectedDate, $this->calendar->lastWeekdayOfWeek($currentDate));
    }

    function testCreateOneMonthPeriod()
    {
        $calendar = new Calendar(new MockClock('2023-06-01'));

        $datePeriod = $calendar->createOneMonthPeriod();

        $this->assertEquals(new DateTimeImmutable('2023-06-01'), $datePeriod->getStartDate());
        $this->assertEquals(new DateTimeImmutable('2024-06-01'), $datePeriod->getEndDate());
        $this->assertEquals(1, $datePeriod->getDateInterval()->format('%m'));
    }
}
