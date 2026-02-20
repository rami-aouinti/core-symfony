<?php

namespace App\Tests\Platform\Application;

use App\Platform\Application\Command\CreatePlatformCommand;
use App\Platform\Application\Handler\CreatePlatformHandler;
use App\Platform\Domain\Exception\InvalidPlatformException;
use App\Platform\Domain\Model\Platform;
use App\Platform\Domain\Repository\PlatformRepository;
use PHPUnit\Framework\TestCase;

final class CreatePlatformHandlerTest extends TestCase
{
    public function testInvokeCreatesAndPersistsPlatformForValidCommand(): void
    {
        $repository = $this->createMock(PlatformRepository::class);
        $repository
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(static function (Platform $platform): bool {
                self::assertSame('Nintendo Switch', $platform->name());
                self::assertSame('Console hybride', $platform->description());
                self::assertSame('#FFAA00', $platform->color());
                self::assertTrue($platform->isActive());

                return true;
            }));

        $handler = new CreatePlatformHandler($repository);

        $result = $handler(new CreatePlatformCommand(
            name: ' Nintendo Switch ',
            description: ' Console hybride ',
            color: '#ffaa00',
            active: true,
        ));

        self::assertSame('Nintendo Switch', $result->name);
        self::assertSame('Console hybride', $result->description);
        self::assertSame('#FFAA00', $result->color);
        self::assertTrue($result->active);
    }

    public function testInvokeThrowsBusinessExceptionAndDoesNotPersistWhenCommandIsInvalid(): void
    {
        $repository = $this->createMock(PlatformRepository::class);
        $repository->expects(self::never())->method('save');

        $handler = new CreatePlatformHandler($repository);

        $this->expectException(InvalidPlatformException::class);
        $this->expectExceptionMessage('Platform name cannot be empty.');

        $handler(new CreatePlatformCommand(
            name: ' ',
            description: 'Console hybride',
            color: '#ffaa00',
            active: true,
        ));
    }
}
