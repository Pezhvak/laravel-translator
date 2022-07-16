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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-translator-config"
```

make sure you add these environment variables to your `.env` file.

```dotenv
DEEPL_AUTH_KEY=<put your deepl token here>
```

## Usage

First migrate the database:

```shell
php artisan migrate
```

Now you can run the following command to detect changes and translate strings:

```shell
php artisan translator:translate
```

Sometimes you want to translate only few locales, you can do this by providing them in the first parameter (separate them by comma if you want to provide multiple locales):

```shell
php artisan translator:translate en,fa
```

If you add a new locale, it won't get translated since there is no changed string in the default language, to do this force the translation:

```shell
php artisan translator:translate [new_locale] --force
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
