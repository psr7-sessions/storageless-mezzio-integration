<?php

declare(strict_types=1);

namespace PSR7Sessions\Mezzio\Storageless;

use Lcobucci\Clock\Clock;
use Mezzio\Session\SessionInterface as MezzioSessionInterface;
use Mezzio\Session\SessionPersistenceInterface as MezzioSessionPersistenceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use UnexpectedValueException;

use function assert;
use function sprintf;

final class SessionPersistence implements MezzioSessionPersistenceInterface
{
    /** @var Clock */
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function initializeSessionFromRequest(ServerRequestInterface $request): MezzioSessionInterface
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

    public function persistSession(MezzioSessionInterface $session, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
