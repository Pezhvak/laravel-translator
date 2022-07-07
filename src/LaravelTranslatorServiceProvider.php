<?php

namespace Pezhvak\LaravelTranslator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Pezhvak\LaravelTranslator\Commands\LaravelTranslatorCommand;

class LaravelTranslatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('LaravelTranslator')
            ->hasConfigFile('laravel-translator')
            ->hasViews()
            ->hasMigration('create_translation_strings_table')
            ->runsMigrations()
            ->hasCommand(LaravelTranslatorCommand::class);
    }
}
