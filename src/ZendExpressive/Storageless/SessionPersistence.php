<?php

declare(strict_types=1);

namespace PSR7Sessions\ZendExpressive\Storageless;

use Lcobucci\Clock\Clock;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use UnexpectedValueException;
use Zend\Expressive\Session\SessionInterface as ZendSessionInterface;
use Zend\Expressive\Session\SessionPersistenceInterface as ZendSessionPersistenceInterface;

use function assert;
use function sprintf;

final class SessionPersistence implements ZendSessionPersistenceInterface
{
    /** @var Clock */
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function initializeSessionFromRequest(ServerRequestInterface $request): ZendSessionInterface
    {
        $storagelessSession = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        assert($storagelessSession instanceof SessionInterface || $storagelessSession === null);
        if (! $storagelessSession instanceof SessionInterface) {
            throw new UnexpectedValueException(
                sprintf(
                    'Please add this following middleware "%s" before execute this method "%s"',
                    SessionMiddleware::class,
                    __METHOD__
                )
            );
        }

        return new SessionAdapter($storagelessSession, $this->clock);
    }

    public function persistSession(ZendSessionInterface $session, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
