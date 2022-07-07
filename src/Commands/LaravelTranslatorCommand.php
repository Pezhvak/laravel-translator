<?php

namespace Pezhvak\LaravelTranslator\Commands;

use Illuminate\Console\Command;
use Pezhvak\LaravelTranslator\LaravelTranslator;

class LaravelTranslatorCommand extends Command
{
    public $signature = 'translator:translate';

    public $description = 'Translate newly changed strings';

    public function handle(): int
    {
        $this->comment('Detecting...');
        $lt = new LaravelTranslator();
        $lt->detectChanges();
        $this->comment('Translating...');
        $lt->translate();
        $this->comment('Generating Files...');
        $lt->generateTranslationFiles();
        $this->info('Done!');

        return self::SUCCESS;
    }
}
