<?php

namespace App\Tests\Platform\Domain\Model;

use App\Platform\Domain\Exception\InvalidPlatformException;
use App\Platform\Domain\Model\Platform;
use PHPUnit\Framework\TestCase;

final class PlatformTest extends TestCase
{
    public function testCreatePlatformWithValidData(): void
    {
        $platform = Platform::create('PlayStation', 'Console Sony', '#12ab34', true);

        self::assertNotSame('', $platform->id());
        self::assertSame('PlayStation', $platform->name());
        self::assertSame('Console Sony', $platform->description());
        self::assertSame('#12AB34', $platform->color());
        self::assertTrue($platform->isActive());
    }

    public function testCreatePlatformFailsWhenNameIsEmpty(): void
    {
        $this->expectException(InvalidPlatformException::class);

        Platform::create('', 'Console Sony', '#12ab34', true);
    }

    public function testCreatePlatformFailsWhenColorIsInvalid(): void
    {
        $this->expectException(InvalidPlatformException::class);

        Platform::create('PlayStation', 'Console Sony', 'blue', true);
    }
}
