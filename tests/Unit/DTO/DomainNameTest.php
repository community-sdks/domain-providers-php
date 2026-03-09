<?php
declare(strict_types=1);

namespace DomainProviders\Tests\Unit\DTO;

use DomainProviders\DTO\DomainName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DomainNameTest extends TestCase
{
    public function testConstructParsesAndNormalizesDomain(): void
    {
        $dto = new DomainName('ExAmPlE.COM');

        self::assertSame('example.com', $dto->full);
        self::assertSame('example', $dto->label);
        self::assertSame('com', $dto->tld);
    }

    public function testConstructRejectsInvalidDomain(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DomainName('localhost');
    }
}
