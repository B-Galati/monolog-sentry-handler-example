<?php
declare(strict_types=1);

namespace App\Handler;

final class Log
{
    public function __construct(public readonly string $message)
    {
    }
}
