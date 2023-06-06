<?php

namespace App\Factory;

use App\IO\CsvExporter;
use App\IO\Exporter;
use Symfony\Component\Filesystem\Filesystem;

final class CsvExporterFactory implements ExporterFactory
{
    public function __construct(private Filesystem $filesystem)
    {
    }

    public function createForPayday(array $paydays): Exporter
    {
        return new CsvExporter($paydays, $this->filesystem);
    }
}