<?php

namespace App\Entity;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\ColorTrait;
use App\Entity\Traits\NameDescriptionTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PlatformRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: PlatformRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Platform
{
    use ActiveTrait;
    use ColorTrait;
    use NameDescriptionTrait;
    use SlugTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSlug(): void
    {
        if (null === $this->name || '' === trim($this->name)) {
            return;
        }

        $slugger = new AsciiSlugger();
        $this->setSlug(strtolower($slugger->slug($this->name)->toString()));
    }
}
