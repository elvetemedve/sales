<?php

namespace App\IO;

use App\Exception\IOException;

interface Exporter
{
    /**
     * @throws IOException
     */
    public function exportToFile(string $filename): void;
}