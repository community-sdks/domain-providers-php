<?php
declare(strict_types=1);

namespace DomainProviders\Tests\Unit\Registry;

use DomainProviders\Contract\DomainProviderInterface;
use DomainProviders\Registry\ProviderNotFoundException;
use DomainProviders\Registry\ProviderRegistry;
use DomainProviders\Tests\Fixtures\FakeProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProviderRegistryTest extends TestCase
{
    public function testRegistersAndResolvesInstance(): void
    {
        $registry = new ProviderRegistry();
        $provider = new FakeProvider();

        $registry->registerInstance('fake', $provider);

        self::assertTrue($registry->has('fake'));
        self::assertSame($provider, $registry->get('fake'));
    }

    public function testRegistersAndResolvesClass(): void
    {
        $registry = new ProviderRegistry();
        $registry->registerClass('fake', FakeProvider::class);

        $resolved = $registry->get('fake');

        self::assertInstanceOf(FakeProvider::class, $resolved);
        self::assertContains('fake', $registry->keys());
    }

    public function testRegistersAndResolvesFactory(): void
    {
        $registry = new ProviderRegistry();
        $registry->registerFactory('factory', static fn (): DomainProviderInterface => new FakeProvider());

        self::assertInstanceOf(FakeProvider::class, $registry->get('factory'));
    }

    public function testGetThrowsWhenProviderMissing(): void
    {
        $this->expectException(ProviderNotFoundException::class);

        (new ProviderRegistry())->get('missing');
    }

    public function testRegisterClassRejectsInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ProviderRegistry())->registerClass('bad', \stdClass::class);
    }

    public function testFactoryMustReturnProvider(): void
    {
        $registry = new ProviderRegistry();
        $registry->registerFactory('invalid', static fn () => new \stdClass());

        $this->expectException(InvalidArgumentException::class);
        $registry->get('invalid');
    }
}
