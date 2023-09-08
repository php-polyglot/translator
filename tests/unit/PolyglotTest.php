<?php

declare(strict_types=1);

namespace TestUnits\Polyglot\Translator;

use PHPUnit\Framework\TestCase;
use Polyglot\MemoryTemplateProvider\MemoryTemplateProvider;
use Polyglot\SimpleTemplateResolver\SimpleTemplateResolver;
use Polyglot\Contract\TemplateProvider\TemplateProvider;
use Polyglot\Contract\TemplateResolver\TemplateResolver;
use Polyglot\Contract\Translator\Translator;
use Polyglot\Translator\Polyglot;

final class PolyglotTest extends TestCase
{
    /**
     * @dataProvider provideTranslate
     */
    public function testTranslate(
        string $domain,
        string $key,
        array $parameters,
        ?string $locale,
        string $translation
    ): void {
        $defaultLocale = $this->getDefaultLocale();
        $templateProvider = $this->getTemplateProvider();
        $templateResolver = $this->getTemplateResolver();
        $translator = $this->getTranslator($defaultLocale, $templateProvider, $templateResolver);
        $this->assertSame($translation, $translator->trans($key, $parameters, $domain, $locale));
    }

    public function provideTranslate(): iterable
    {
        return [
            ['no-template', 'Hello, World!', [], 'en_US', 'Hello, World!'],
            ['no-template', 'Привет, Мир!', [], 'ru_RU', 'Привет, Мир!'],

            ['simple', 'hello_world', [], 'en_US', 'Hello, World!'],
            ['simple', 'hello_world', [], 'ru_RU', 'Привет, Мир!'],

            ['parametrized', 'hello_username', [], 'en_US', 'Hello, {username}!'],
            ['parametrized', 'hello_username', ['username' => 'Polyglot'], 'en_US', 'Hello, Polyglot!'],
            ['parametrized', 'hello_username', [], 'ru_RU', 'Привет, {username}!'],
            ['parametrized', 'hello_username', ['username' => 'Полиглот'], 'ru_RU', 'Привет, Полиглот!'],
        ];
    }

    private function getTranslator(
        string $defaultLocale,
        TemplateProvider $templateProvider,
        TemplateResolver $templateResolver
    ): Translator {
        return new Polyglot($defaultLocale, $templateProvider, $templateResolver);
    }

    private function getDefaultLocale(): string
    {
        return 'en_US';
    }

    private function getTemplateProvider(): TemplateProvider
    {
        return (new MemoryTemplateProvider())
            ->set('simple', 'hello_world', 'en_US', 'Hello, World!')
            ->set('simple', 'hello_world', 'ru_RU', 'Привет, Мир!')
            ->set('parametrized', 'hello_username', 'en_US', 'Hello, {username}!')
            ->set('parametrized', 'hello_username', 'ru_RU', 'Привет, {username}!')
            ;
    }

    private function getTemplateResolver(): TemplateResolver
    {
        return new SimpleTemplateResolver();
    }
}
