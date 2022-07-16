<?php

namespace Pezhvak\LaravelTranslator\Commands;

use Illuminate\Console\Command;
use Pezhvak\LaravelTranslator\LaravelTranslator;

class LaravelTranslatorCommand extends Command
{
    public $signature = 'translator:translate {locale?} {--force}';

    public $description = 'Translate newly changed strings';

    public function handle(): int
    {
        $locals = $this->argument('locale')
            ? explode(',', $this->argument('locale'))
            : null;
        $this->comment('Detecting...');
        $lt = new LaravelTranslator();
        $lt->detectChanges();
        $this->comment('Translating...');
        $lt->translate($locals, $this->option('force'));
        $this->comment('Generating Files...');
        $lt->generateTranslationFiles($locals);
        $this->info('Done!');

        return self::SUCCESS;
    }
}
