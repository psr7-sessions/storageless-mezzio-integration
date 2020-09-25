<?php

declare(strict_types=1);

namespace PSR7SessionsTest\Mezzio\Storageless;

use Lcobucci\Clock\Clock;
use Mezzio\Session\SessionInterface as MezzioSessionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Mezzio\Storageless\SessionPersistence;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use UnexpectedValueException;

use function assert;
use function sprintf;

/** @covers \PSR7Sessions\Mezzio\Storageless\SessionPersistence */
final class SessionPersistenceTest extends TestCase
{
    public function testInitializeSessionFromRequestWithMissingPsr7SessionAttribute(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage(sprintf(
            'Please add this following middleware "%s" before execute this method "%s::initializeSessionFromRequest"',
            SessionMiddleware::class,
            SessionPersistence::class
        ));

        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        assert($request instanceof ServerRequestInterface);
        $request
            ->expects(self::once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE, null)
            ->willReturn(null);

        $persistence = new SessionPersistence($this->createMock(Clock::class));
        $persistence->initializeSessionFromRequest($request);
    }

    public function testInitializeSessionFromRequestWithPsr7SessionAttribute(): void
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        assert($session instanceof SessionInterface);
        $session->expects(self::never())->method(self::anything());

        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        assert($request instanceof ServerRequestInterface);
        $request
            ->expects(self::once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE, null)
            ->willReturn($session);

        $persistence = new SessionPersistence($this->createMock(Clock::class));
        $persistence->initializeSessionFromRequest($request);
    }

    public function testPersistSession(): void
    {
        $mezzioSession = $this->getMockBuilder(MezzioSessionInterface::class)->getMock();
        assert($mezzioSession instanceof MezzioSessionInterface);
        $mezzioSession->expects(self::never())->method(self::anything());

        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        assert($response instanceof ResponseInterface);
        $response->expects(self::never())->method(self::anything());

        $persistence = new SessionPersistence($this->createMock(Clock::class));

        self::assertSame($response, $persistence->persistSession($mezzioSession, $response));
    }
}
