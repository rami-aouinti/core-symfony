<?php

namespace App\Platform\Application\Command;

final readonly class CreatePlatformCommand
{
    public function __construct(
        public string $name,
        public string $description,
        public string $color,
        public bool $active,
    ) {
    }
}
