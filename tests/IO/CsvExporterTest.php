<?php

namespace App\Tests\IO;

use App\Exception\IOException;
use App\IO\CsvExporter;
use App\Payday;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\IOException as FilesystemIOException;
use Symfony\Component\Filesystem\Filesystem;

final class CsvExporterTest extends TestCase
{
    private const EXPECTED_CSV_CONTENT = <<<TEXT
Month,"Salary Date","Bonus Date"
January,2023-01-01,2023-01-02
February,2023-02-01,2023-02-02

TEXT;

    function testExportToFile(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->expects($this->once())->method('dumpFile')->with($this->identicalTo('testfile'), $this->callback(function ($stream) {
            $this->assertEquals(self::EXPECTED_CSV_CONTENT, stream_get_contents($stream));

            return true;
        }));

        $csvExporter = new CsvExporter(
            [
                new Payday(new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-01-02')),
                new Payday(new DateTimeImmutable('2023-02-01'), new DateTimeImmutable('2023-02-02')),
            ],
            $filesystem
        );

        $csvExporter->exportToFile('testfile');
    }

    function testExportToFileFailedWithIOException(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('dumpFile')->willThrowException(new FilesystemIOException('testing IO errors'));

        $this->expectException(IOException::class);

        $csvExporter = new CsvExporter([], $filesystem);
        $csvExporter->exportToFile('testfile');
    }
}
