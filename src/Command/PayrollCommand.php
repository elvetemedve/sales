<?php

namespace App\Command;

use App\Domain\Calculator\BonusDayCalculator;
use App\Domain\Calculator\SalaryDayCalculator;
use App\Domain\Payroll;
use App\Exception\IOException;
use App\Factory\CsvExporterFactory;
use App\Time\Calendar;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:payroll-export',
    description: 'This command exports the payroll data into a file in CSV format.'
)]
class PayrollCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Output filename');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $filename = $input->getArgument('filename');
            $output->writeln(sprintf('Exporting payroll data to file "%s" ...', $filename));

            $payroll = $this->createPayroll();

            $payroll->generate($filename);

            return Command::SUCCESS;
        } catch (IOException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function createPayroll(): Payroll
    {
        $calendar = new Calendar(new Clock());

        return new Payroll(
            $calendar,
            new SalaryDayCalculator($calendar),
            new BonusDayCalculator($calendar),
            new CsvExporterFactory(new Filesystem())
        );
    }
}