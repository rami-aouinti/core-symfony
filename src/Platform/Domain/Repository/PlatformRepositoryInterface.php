<?php

namespace App\Platform\Domain\Repository;

use App\Platform\Domain\Model\Platform;

interface PlatformRepositoryInterface
{
    public function save(Platform $platform): void;
}
