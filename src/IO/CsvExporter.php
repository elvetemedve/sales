<?php

namespace App\IO;

use App\Exception\IOException;
use App\Payday;
use Symfony\Component\Filesystem\Exception\IOException as FilesystemIOException;
use Symfony\Component\Filesystem\Filesystem;

final class CsvExporter implements Exporter
{
    private const HEADER = ['Month', 'Salary Date', 'Bonus Date'];

    /**
     * @param Payday[] $paydays
     */
    public function __construct(private array $paydays, private Filesystem $filesystem)
    {
    }

    public function exportToFile(string $filename): void
    {
        try {
            $csvStream = fopen('php://memory', 'w+');

            $this->convertData($csvStream);

            fseek($csvStream, 0);

            $this->filesystem->dumpFile($filename, $csvStream);

            fclose($csvStream);
        } catch (FilesystemIOException $exception) {
            throw IOException::createWriteFailed($filename);
        }
    }

    private function convertData($csvStream): void
    {
        fputcsv($csvStream, self::HEADER);

        foreach ($this->paydays as $payday) {
            assert($payday instanceof Payday);

            fputcsv($csvStream, [
                $payday->getSalaryDate()->format('F'),
                $payday->getSalaryDate()->format('Y-m-d'),
                $payday->getBonusDate()->format('Y-m-d'),
            ]);
        }
    }
}