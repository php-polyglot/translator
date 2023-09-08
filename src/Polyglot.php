<?php

declare(strict_types=1);

namespace Polyglot\Translator;

use Polyglot\Contract\TemplateProvider\Exception\TemplateNotFound;
use Polyglot\Contract\TemplateProvider\TemplateProvider;
use Polyglot\Contract\TemplateResolver\TemplateResolver;
use Polyglot\Contract\Translator\Translator;

final class Polyglot implements Translator
{
    private string $defaultLocale;
    private TemplateProvider $templateProvider;
    private TemplateResolver $templateResolver;

    public function __construct(
        string $defaultLocale,
        TemplateProvider $templateProvider,
        TemplateResolver $templateResolver
    ) {
        $this->defaultLocale = $defaultLocale;
        $this->templateProvider = $templateProvider;
        $this->templateResolver = $templateResolver;
    }

    public function need(?string $id, string $domain = null, string $locale = null): void
    {
        $id = $this->normalizeId($id);
        $domain = $this->normalizeDomain($domain);
        $locale = $this->normalizeLocale($locale);

        $this->templateProvider->need($domain, $id, $locale);
    }

    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $id = $this->normalizeId($id);
        $domain = $this->normalizeDomain($domain);
        $locale = $this->normalizeLocale($locale);

        try {
            $template = $this->templateProvider->get($domain, $id, $locale);
        } catch (TemplateNotFound $exception) {
            $template = $id;
        }

        return $this->templateResolver->resolve($template, $parameters, $locale);
    }

    public function flush(): void
    {
        $this->templateProvider->flush();
    }

    private function normalizeId(?string $id): string
    {
        if (is_null($id)) {
            return '';
        }
        return $id;
    }

    private function normalizeDomain(?string $domain): string
    {
        if (is_null($domain)) {
            return 'messages';
        }
        return $domain;
    }

    private function normalizeLocale(?string $locale): string
    {
        if (is_null($locale)) {
            return $this->defaultLocale;
        }
        return $locale;
    }
}
