<?php

namespace Pezhvak\LaravelTranslator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pezhvak\LaravelTranslator\LaravelTranslator
 */
class LaravelTranslator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaravelTranslator';
    }
}
