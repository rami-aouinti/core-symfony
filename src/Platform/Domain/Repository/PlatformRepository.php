<?php

namespace App\Platform\Domain\Repository;

use App\Platform\Domain\Model\Platform;

interface PlatformRepository
{
    public function save(Platform $platform): void;
}
