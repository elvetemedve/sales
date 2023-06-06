<?php

namespace App\Exception;

use Exception;

final class IOException extends Exception
{

    public static function createWriteFailed(string $filename): self
    {
        return new self(sprintf('Failed to write to file: %s', $filename));
    }
}