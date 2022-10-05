<?php

declare(strict_types=1);

namespace PSR7Sessions\Mezzio\Storageless;

use Mezzio\Session\SessionIdentifierAwareInterface;
use Mezzio\Session\SessionInterface;

interface SessionThatIsIdentifierAware extends SessionInterface, SessionIdentifierAwareInterface {}
