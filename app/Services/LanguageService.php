<?php

namespace App\Services;

class LanguageService
{
    private array $config;

    public function __construct()
    {
        $this->config = config('lang');
    }

    public function getDefaultLanguage(): array
    {
        return $this->config['default_language'];
    }

    public function getLanguagesList(): array
    {
        return $this->config['languages'];
    }

    public function getLocaleMapping(): array
    {
        return $this->config['locale_mapping'];
    }

    public function resolve(string $language = null): string
    {
        $languages = $this->getLanguagesList();
        $localeMapping = $this->getLocaleMapping();

        if (isset($localeMapping[$language])) {
            $language = $localeMapping[$language];
        }

        if (isset($languages[$language])) {
            return $language;
        }

        return $this->getDefaultLanguage();
    }
}
