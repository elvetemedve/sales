# Sales App
The purpose of this application is to demonstrate PHP 8.x capabilities as well as writing testable, maintainable code.

## Features

The task is to create a small command-line utility to help a fictional company determine the dates on which they need to pay salaries to their Sales Department.
The company handles their Sales payroll in the following way:

- Sales staff get a regular fixed base monthly salary, plus a monthly bonus
- The base salaries are paid on the last day of the month, unless that day is a Saturday, a Sunday
  (weekend). In that case, salaries are paid before the weekend. For the sake of this application,
  ignore public holidays.
- On the 15th of every month bonuses are paid for the previous month, unless that day is a
  weekend. In that case, they are paid the first Wednesday after the 15th

### Input
The filename of the exported data must be provided by the user.

### Output
The output of the utility should be a CSV file, containing the payment dates for the next twelve months.
The CSV file should contain a column for the month name, a column that contains the salary payment date for that month,
and a column that contains the bonus payment date.

## Installation

The application is built with the [Symfony Console](https://symfony.com/doc/current/console.html#creating-a-command) component. But the domain models are decoupled from the framework entirely. Apart from the framework these Symfony components are used (but any other 3rd-party code could have been used which provides similar functionality):
- symfony/filesystem
- symfony/clock

Run `composer install`.

## Executing tests

Run `vendor/bin/phpunit`.

## Executing the app

Run `bin/console app:payroll-export <filename>`.
