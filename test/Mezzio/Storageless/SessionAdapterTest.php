<?php

declare(strict_types=1);

namespace PSR7SessionsTest\Mezzio\Storageless;

use DateTimeImmutable;
use Lcobucci\Clock\Clock;
use Lcobucci\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;
use PSR7Sessions\Mezzio\Storageless\SessionAdapter;
use PSR7Sessions\Storageless\Session\SessionInterface;
use stdClass;

use function assert;

/** @covers \PSR7Sessions\Mezzio\Storageless\SessionAdapter */
final class SessionAdapterTest extends TestCase
{
    public function testToArray(): void
    {
        $object      = new stdClass();
        $object->key = 'value';

        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('jsonSerialize')->with()->willReturn($object);

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));

        self::assertSame(['key' => 'value'], $sessionAdapter->toArray());
    }

    public function testGet(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('get')->with('key', null)->willReturn('value');

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));

        self::assertSame('value', $sessionAdapter->get('key'));
    }

    public function testHas(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('has')->with('key')->willReturn(true);

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));

        self::assertTrue($sessionAdapter->has('key'));
    }

    public function testSet(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('set')->with('key', 'value');

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));
        $sessionAdapter->set('key', 'value');
    }

    public function testUnset(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('remove')->with('key');

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));
        $sessionAdapter->unset('key');
    }

    public function testClear(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('clear')->with();

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));
        $sessionAdapter->clear();
    }

    public function testHasChanged(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('hasChanged')->with()->willReturn(true);

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));

        self::assertTrue($sessionAdapter->hasChanged());
    }

    public function testRegenerate(): void
    {
        $clock = new FrozenClock(new DateTimeImmutable('2019-01-01T00:00:00+00:00'));

        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('set')->with('_regenerated', $clock->now()->getTimestamp());

        $sessionAdapter = new SessionAdapter($session, $clock);
        $sessionAdapter->regenerate();
    }

    public function testIsRegenerated(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::once())->method('has')->with('_regenerated')->willReturn(true);

        $sessionAdapter = new SessionAdapter($session, $this->createMock(Clock::class));

        self::assertTrue($sessionAdapter->isRegenerated());
    }
}
