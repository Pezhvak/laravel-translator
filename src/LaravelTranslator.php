<?php

namespace Pezhvak\LaravelTranslator;

use DeepL\TranslateTextOptions;
use DeepL\Translator;
use Illuminate\Support\Arr;
use Pezhvak\LaravelTranslator\Models\TranslationString;

class LaravelTranslator
{
    private Translator $_translator;

    /**
     * @throws \DeepL\DeepLException
     */
    public function __construct()
    {
        $this->_translator = new Translator(config('laravel-translator.deepl.auth_key'));
    }

    public function getDefaultLocale(): string
    {
        return config('app.locale');
    }

    public function getLocales($exclude_default_locale = false): array
    {
        $exclude_paths = ['..', '.'];
        if ($exclude_default_locale) $exclude_paths[] = $this->getDefaultLocale();
        return array_diff(scandir(lang_path()), $exclude_paths);
    }

    public function loadTranslatedStrings(): static
    {
        foreach ($this->getLocales(true) as $locale) {
            # preparing data
            $localeDirectory = lang_path($locale) . '/';
            $translationFiles = array_diff(scandir($localeDirectory), ['..', '.']);
            $start_time = now();

            # fetching strings
            foreach ($translationFiles as $translationFile) {
                if (in_array($translationFile, config('laravel-translator.files_to_ignore'))) continue;
                if (!file_exists($localeDirectory . $translationFile)) continue;
                $translations = Arr::dot(include $localeDirectory . $translationFile);
                foreach ($translations as $key => $value) {
                    if (!is_string($value)) continue;
                    TranslationString::updateOrCreate([
                        'locale' => $locale,
                        'file'   => $translationFile,
                        'key'    => $key,
                    ], [
                        'value'             => $value,
                        'needs_translation' => false,
                    ]);
                }
            }
        }
        return $this;
    }

    public function detectChanges(): static
    {
        # preparing data
        $defaultLocale = $this->getDefaultLocale();
        $defaultLocaleDirectory = lang_path($defaultLocale) . '/';
        $translationFiles = array_diff(scandir($defaultLocaleDirectory), ['..', '.']);

        $start_time = now();

        # fetching strings
        foreach ($translationFiles as $translationFile) {
            if (in_array($translationFile, config('laravel-translator.files_to_ignore'))) continue;
            if (!file_exists($defaultLocaleDirectory . $translationFile)) continue;
            $translations = Arr::dot(include $defaultLocaleDirectory . $translationFile);
            foreach ($translations as $key => $value) {
                if (!is_string($value)) continue;
                TranslationString::updateOrCreate([
                    'locale' => $defaultLocale,
                    'file'   => $translationFile,
                    'key'    => $key,
                ], [
                    'value' => $value,
                ]);
            }
        }

        # mark changed strings as needs translation
        TranslationString::where('locale', $defaultLocale)
            ->where('updated_at', '>=', $start_time)
            ->update([
                'needs_translation' => true,
            ]);

        return $this;
    }

    public function translate(array $to_locales = [], $force_all = false): static
    {
        # fetching strings which needs translation
        $needs_translation = TranslationString::query()
            ->where('locale', $this->getDefaultLocale())
            ->when(!$force_all, function ($query) {
                $query->where('needs_translation', true);
            })
            ->get();
        if ($needs_translation->count() == 0) return $this;

        # iterating locales which strings need to get translated to
        $to_locales = count($to_locales) > 0 ? $to_locales : $this->getLocales();
        foreach ($needs_translation->chunk(50) as $iteration => $translation_chunk) {
            foreach ($to_locales as $to_locale) {
                if ($to_locale === $this->getDefaultLocale()) continue;
                $translations = $this->_translateText($translation_chunk
                    ->pluck('value')
                    ->toArray(), $this->getDefaultLocale(), $to_locale);
                foreach ($translations as $index => $translation) {
                    $index = $index + ($iteration * 50);
                    try {
                        TranslationString::updateOrCreate([
                            'locale' => $to_locale,
                            'file'   => $translation_chunk[$index]->file,
                            'key'    => $translation_chunk[$index]->key,
                        ], [
                            'value'             => $translation,
                            'needs_translation' => false,
                        ]);
                    } catch (\Exception $exception) {
                        dd($exception, $translation_chunk, $translations);
                    }
                }
            }
            TranslationString::whereIn('id', $translation_chunk->pluck('id'))
                ->update(['needs_translation' => false]);
        }
        return $this;
    }

    public function generateTranslationFiles(array $to_locales = []): void
    {
        $to_locales = count($to_locales) > 0 ? $to_locales : $this->getLocales(true);

        foreach ($to_locales as $to_locale) {
            $strings = TranslationString::query()->where('locale', $to_locale)->get();
            foreach ($strings->groupBy('file') as $filename => $translations) {
                $fileContents = '<?php return ' . var_export(Arr::undot($translations->pluck('value', 'key')), true) . ';';
                file_put_contents(lang_path($to_locale . '/' . $filename), $fileContents);
            }
        }
    }


    /**
     * @throws \DeepL\DeepLException
     */
    private function _translateText(array|string $texts, string $source_lang, string $target_lang): array|\DeepL\TextResult
    {
        if (is_array($texts))
            $texts = array_map(function ($text) {
                return $this->addIgnoreTagForVariables($text);
            }, $texts);

        return array_map(function ($response) {
            return $this->_removeIgnoreTags($response);
        }, $this->_translator
            ->translateText($texts, $source_lang, $target_lang, [
                TranslateTextOptions::TAG_HANDLING => 'xml',
                TranslateTextOptions::IGNORE_TAGS  => 'ignore-variable',
                TranslateTextOptions::FORMALITY    => in_array(strtolower($target_lang), [
                    'de', 'fr', 'it', 'es', 'nl', 'pl', 'pt-pt', 'pt-br', 'ru']) ? 'less' : 'default',
            ]));
    }

    private function addIgnoreTagForVariables($text)
    {
        if (empty($text)) return "<ignore-variable></ignore-variable>";
        if (preg_match_all('/(:\w+|{\w+})/', $text, $matches, PREG_PATTERN_ORDER)) {
            foreach ($matches[1] as $match) {
                $text = str_replace($match, '<ignore-variable>' . $match . '</ignore-variable>', $text);
            }
        }
        return $text;
    }

    private function _removeIgnoreTags($text): string
    {
        return strip_tags($text);
    }
}
