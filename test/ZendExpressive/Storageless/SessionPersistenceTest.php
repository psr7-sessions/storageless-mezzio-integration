<?php

declare(strict_types=1);

namespace PSR7SessionsTest\ZendExpressive\Storageless;

use Lcobucci\Clock\Clock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use PSR7Sessions\ZendExpressive\Storageless\SessionPersistence;
use UnexpectedValueException;
use Zend\Expressive\Session\SessionInterface as ZendSessionInterface;
use function sprintf;

/** @covers \PSR7Sessions\ZendExpressive\Storageless\SessionPersistence */
final class SessionPersistenceTest extends TestCase
{
    public function testInitializeSessionFromRequestWithMissingPsr7SessionAttribute() : void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage(sprintf(
            'Please add this following middleware "%s" before execute this method "%s::initializeSessionFromRequest"',
            SessionMiddleware::class,
            SessionPersistence::class
        ));

        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request
            ->expects(self::once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE, null)
            ->willReturn(null);

        $persistence = new SessionPersistence($this->createMock(Clock::class));
        $persistence->initializeSessionFromRequest($request);
    }

    public function testInitializeSessionFromRequestWithPsr7SessionAttribute() : void
    {
        /** @var SessionInterface|MockObject $session */
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        $session->expects(self::never())->method(self::anything());

        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request
            ->expects(self::once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE, null)
            ->willReturn($session);

        $persistence = new SessionPersistence($this->createMock(Clock::class));
        $persistence->initializeSessionFromRequest($request);
    }

    public function testPersistSession() : void
    {
        /** @var ZendSessionInterface|MockObject $zendSession */
        $zendSession = $this->getMockBuilder(ZendSessionInterface::class)->getMock();
        $zendSession->expects(self::never())->method(self::anything());

        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->expects(self::never())->method(self::anything());

        $persistence = new SessionPersistence($this->createMock(Clock::class));

        self::assertSame($response, $persistence->persistSession($zendSession, $response));
    }
}
