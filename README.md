# PSR-7 Storage-less HTTP Sessions - Mezzio Session Integration

[![Build Status](https://travis-ci.org/psr7-sessions/storageless-mezzio-integration.svg)](https://travis-ci.org/psr7-sessions/storageless-mezzio-integration)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/psr7-sessions/storageless-mezzio-integration/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/psr7-sessions/storageless-mezzio-integration/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/psr7-sessions/storageless-mezzio-integration/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/psr7-sessions/storageless-mezzio-integration/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/psr7-sessions/storageless-mezzio-integration.svg)](https://packagist.org/packages/psr7-sessions/storageless-mezzio-integration)
[![Packagist](https://img.shields.io/packagist/vpre/psr7-sessions/storageless-mezzio-integration.svg)](https://packagist.org/packages/psr7-sessions/storageless-mezzio-integration)

This integration allows you to use storageless as an implementation for [mezzio-session][1]

#### Installation

```sh
composer require lcobucci/clock \
                 psr7-sessions/storageless \
                 psr7-sessions/storageless-mezzio-integration
```

#### Symmetric key

```php
use Lcobucci\Clock\SystemClock;
use Mezzio\Session\SessionMiddleware;
use PSR7Sessions\Mezzio\Storageless\SessionPersistence;
use PSR7Sessions\Storageless\Http\SessionMiddleware as PSR7SessionMiddleware;

$app = \Mezzio\AppFactory::create();
$app->pipe(PSR7SessionMiddleware::fromSymmetricKeyDefaults(
    'OpcMuKmoxkhzW0Y1iESpjWwL/D3UBdDauJOe742BJ5Q=',
    1200
));
$app->pipe(new SessionMiddleware(new SessionPersistence(new SystemClock())));
```

#### Asymmetric key

```php
use Lcobucci\Clock\SystemClock;
use Mezzio\Session\SessionMiddleware;
use PSR7Sessions\Mezzio\Storageless\SessionPersistence;
use PSR7Sessions\Storageless\Http\SessionMiddleware as PSR7SessionMiddleware;

$app = \Mezzio\AppFactory::create();
$app->pipe(PSR7SessionMiddleware::fromSymmetricKeyDefaults(
    file_get_contents('/path/to/private_key.pem'),
    file_get_contents('/path/to/public_key.pem'),
    1200
));
$app->pipe(new SessionMiddleware(new SessionPersistence(new SystemClock())));
```

[1]: https://github.com/mezzio/mezzio-session

### Contributing

Please refer to the [contributing notes](CONTRIBUTING.md).

### License

This project is made public under the [MIT LICENSE](LICENSE).
