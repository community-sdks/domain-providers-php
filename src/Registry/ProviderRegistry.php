<?php
declare(strict_types=1);

namespace DomainProviders\Registry;

use DomainProviders\Contract\DomainProviderInterface;
use InvalidArgumentException;

final class ProviderRegistry
{
    /** @var array<string, callable(): DomainProviderInterface> */
    private array $factories = [];

    public function registerInstance(string $key, DomainProviderInterface $provider): self
    {
        $this->factories[$key] = static fn (): DomainProviderInterface => $provider;
        return $this;
    }

    /** @param class-string<DomainProviderInterface> $providerClass */
    public function registerClass(string $key, string $providerClass): self
    {
        if (!is_subclass_of($providerClass, DomainProviderInterface::class)) {
            throw new InvalidArgumentException(sprintf('Class "%s" must implement %s.', $providerClass, DomainProviderInterface::class));
        }

        $this->factories[$key] = static fn (): DomainProviderInterface => new $providerClass();
        return $this;
    }

    /** @param callable(): DomainProviderInterface $factory */
    public function registerFactory(string $key, callable $factory): self
    {
        $this->factories[$key] = $factory;
        return $this;
    }

    public function has(string $key): bool
    {
        return isset($this->factories[$key]);
    }

    public function get(string $key): DomainProviderInterface
    {
        if (!$this->has($key)) {
            throw new ProviderNotFoundException(sprintf('Provider "%s" is not registered.', $key));
        }

        $provider = ($this->factories[$key])();

        if (!$provider instanceof DomainProviderInterface) {
            throw new InvalidArgumentException(sprintf('Provider factory "%s" must return %s.', $key, DomainProviderInterface::class));
        }

        return $provider;
    }

    /** @return list<string> */
    public function keys(): array
    {
        return array_values(array_keys($this->factories));
    }
}
