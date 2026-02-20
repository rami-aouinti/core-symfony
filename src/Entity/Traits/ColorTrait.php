<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ColorTrait
{
    #[ORM\Column(length: 7, nullable: true)]
    private ?string $color = null;

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }
}
