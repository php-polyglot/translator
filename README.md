# polyglot/translator

> A [polyglot](https://packagist.org/packages/polyglot/) translator.

# Install

```shell
composer require polyglot/translator:^1.0
```

# Using

```php
<?php

/**
 * @var \Polyglot\Contract\TemplateProvider\TemplateProvider $templateProvider
 * @var \Polyglot\Contract\TemplateResolver\TemplateResolver $templateResolver
 */
$polyglot = new \Polyglot\Translator\Polyglot('en_US', $templateProvider, $templateResolver);

$french = $polyglot->trans('translation_id', [], 'domain', 'fr_FR');
$default = $polyglot->trans('translation_id', [], 'domain');
```
