<?php

namespace App\Tests\Controller;

use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PlatformControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);
    }

    public function testIndexIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/platform/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Platforms');
    }

    public function testCreatePlatform(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/platform/new');

        $client->submit($crawler->selectButton('Save')->form([
            'platform[name]' => 'PlayStation',
            'platform[description]' => 'Sony console',
            'platform[color]' => '#123456',
            'platform[active]' => 1,
        ]));

        self::assertResponseRedirects('/platform/');
        $client->followRedirect();
        self::assertSelectorTextContains('table', 'PlayStation');
    }

    public function testShowPlatform(): void
    {
        $platform = $this->createPlatform('Nintendo Switch');

        $client = static::createClient();
        $client->request('GET', sprintf('/platform/%d', $platform->getId()));

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Nintendo Switch');
    }

    public function testEditPlatform(): void
    {
        $platform = $this->createPlatform('Xbox One');

        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/platform/%d/edit', $platform->getId()));

        $client->submit($crawler->selectButton('Update')->form([
            'platform[name]' => 'Xbox Series',
            'platform[description]' => 'Updated',
            'platform[color]' => '#654321',
            'platform[active]' => 0,
        ]));

        self::assertResponseRedirects('/platform/');
        $client->followRedirect();
        self::assertSelectorTextContains('table', 'Xbox Series');
    }

    public function testDeletePlatform(): void
    {
        $platform = $this->createPlatform('Steam');

        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/platform/%d/edit', $platform->getId()));
        $token = $crawler->filter('input[name="_token"]')->attr('value');

        $client->request('POST', sprintf('/platform/%d', $platform->getId()), ['_token' => $token]);

        self::assertResponseRedirects('/platform/');
        $client->followRedirect();
        self::assertSelectorTextNotContains('table', 'Steam');
    }

    private function createPlatform(string $name): Platform
    {
        $platform = (new Platform())
            ->setName($name)
            ->setDescription('Description '.$name)
            ->setColor('#abcdef')
            ->setActive(true);

        $this->entityManager->persist($platform);
        $this->entityManager->flush();

        return $platform;
    }
}
