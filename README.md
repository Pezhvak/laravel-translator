# laravel-translator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pezhvak/laravel-translator.svg?style=flat-square)](https://packagist.org/packages/pezhvak/laravel-translator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/pezhvak/laravel-translator/run-tests?label=tests)](https://github.com/pezhvak/laravel-translator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/pezhvak/laravel-translator/Check%20&%20fix%20styling?label=code%20style)](https://github.com/pezhvak/laravel-translator/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/pezhvak/laravel-translator.svg?style=flat-square)](https://packagist.org/packages/pezhvak/laravel-translator)

## Installation

You can install the package via composer:

```bash
composer require pezhvak/laravel-translator
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-translator-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-translator-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-translator-views"
```

## Usage

```php
$laravel_translator = new Pezhvak\LaravelTranslator();
echo $laravel_translator->echoPhrase('Hello, Pezhvak/laravel-translator!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Pezhvak/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Pezhvak](https://github.com/Pezhvak)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
