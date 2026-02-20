<?php

namespace App\Tests\Platform\Domain\Model;

use App\Platform\Domain\Exception\InvalidPlatformException;
use App\Platform\Domain\Model\Platform;
use PHPUnit\Framework\TestCase;

final class PlatformTest extends TestCase
{
    public function testCreateNormalizesAndPreservesInvariants(): void
    {
        $platform = Platform::create('  PlayStation  ', '  Console Sony  ', '#12ab34', true);

        self::assertNotSame('', $platform->id());
        self::assertSame('PlayStation', $platform->name());
        self::assertSame('Console Sony', $platform->description());
        self::assertSame('#12AB34', $platform->color());
        self::assertTrue($platform->isActive());
    }

    public function testCreateFailsWhenNameIsEmptyAfterTrim(): void
    {
        $this->expectException(InvalidPlatformException::class);
        $this->expectExceptionMessage('Platform name cannot be empty.');

        Platform::create('   ', 'Console Sony', '#12ab34', true);
    }

    public function testCreateFailsWhenDescriptionIsEmptyAfterTrim(): void
    {
        $this->expectException(InvalidPlatformException::class);
        $this->expectExceptionMessage('Platform description cannot be empty.');

        Platform::create('PlayStation', '   ', '#12ab34', true);
    }

    public function testCreateFailsWhenColorIsInvalid(): void
    {
        $this->expectException(InvalidPlatformException::class);
        $this->expectExceptionMessage('Platform color must be a valid hexadecimal color (#RRGGBB).');

        Platform::create('PlayStation', 'Console Sony', 'blue', true);
    }

    public function testReconstituteKeepsExistingStateAndNormalizesColor(): void
    {
        $platform = Platform::reconstitute(
            id: 'platform-id',
            name: 'Xbox',
            description: 'Console Microsoft',
            color: '#ffaa00',
            active: false,
        );

        self::assertSame('platform-id', $platform->id());
        self::assertSame('Xbox', $platform->name());
        self::assertSame('Console Microsoft', $platform->description());
        self::assertSame('#FFAA00', $platform->color());
        self::assertFalse($platform->isActive());
    }
}
