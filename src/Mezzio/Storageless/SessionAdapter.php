<?php

declare(strict_types=1);

namespace PSR7Sessions\Mezzio\Storageless;

use Lcobucci\Clock\Clock;
use Mezzio\Session\SessionInterface as MezzioSessionInterface;
use PSR7Sessions\Storageless\Session\SessionInterface;

final class SessionAdapter implements MezzioSessionInterface
{
    private const SESSION_REGENERATED_NAME = '_regenerated';

    /** @var SessionInterface */
    private $session;

    /** @var Clock */
    private $clock;

    public function __construct(SessionInterface $session, Clock $clock)
    {
        $this->session = $session;
        $this->clock   = $clock;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return (array) $this->session->jsonSerialize();
    }

    /**
     * @param array<array-key, mixed>|object|scalar|null $default
     *
     * @return array<array-key, mixed>|object|scalar|null
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function get(string $name, $default = null)
    {
        return $this->session->get($name, $default);
    }

    public function has(string $name): bool
    {
        return $this->session->has($name);
    }

    /**
     * @param array<array-key, mixed>|object|scalar|null $value
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function set(string $name, $value): void
    {
        $this->session->set($name, $value);
    }

    public function unset(string $name): void
    {
        $this->session->remove($name);
    }

    public function clear(): void
    {
        $this->session->clear();
    }

    public function hasChanged(): bool
    {
        return $this->session->hasChanged();
    }

    public function regenerate(): MezzioSessionInterface
    {
        $this->session->set(self::SESSION_REGENERATED_NAME, $this->timestamp());

        return $this;
    }

    public function isRegenerated(): bool
    {
        return $this->session->has(self::SESSION_REGENERATED_NAME);
    }

    private function timestamp(): int
    {
        return $this->clock->now()->getTimestamp();
    }
}
