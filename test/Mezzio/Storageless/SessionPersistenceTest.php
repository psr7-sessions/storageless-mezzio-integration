<?php

declare(strict_types=1);

namespace PSR7SessionsTest\Mezzio\Storageless;

use Lcobucci\Clock\Clock;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Mezzio\Storageless\SessionPersistence;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use PSR7SessionsTest\Mezzio\Storageless\SessionThatIsIdentifierAware;
use UnexpectedValueException;

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

        $request = $this->createMock(ServerRequestInterface::class);
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
        $session = $this->createMock(SessionInterface::class);
        $session->expects(self::never())->method(self::anything());

        $request = $this->createMock(ServerRequestInterface::class);
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
        $mezzioSession = $this->createMock(SessionThatIsIdentifierAware::class);
        $mezzioSession->expects(self::never())->method(self::anything());

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::never())->method(self::anything());

        $persistence = new SessionPersistence($this->createMock(Clock::class));

        self::assertSame($response, $persistence->persistSession($mezzioSession, $response));
    }
}
