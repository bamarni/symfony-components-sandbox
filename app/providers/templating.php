<?php

use Symfony\Bundle\FrameworkBundle;
use Symfony\Component\Templating;
use Symfony\Component\Translation;

$container['templating.engine'] = $container->share(function($c) {
    return new Templating\PhpEngine(
        new Sandbox\Templating\TemplateNameParser,
        new Templating\Loader\FilesystemLoader(__DIR__.'/../../%name%')
    );
});

// Templating helpers
$templatingHelpers = array();
$templatingHelpers[] = new FrameworkBundle\Templating\Helper\FormHelper($container['templating.engine'], null, array(
    __DIR__.'/../../vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle/Resources/views/Form'
));
$templatingHelpers[] = new FrameworkBundle\Templating\Helper\TranslatorHelper(
    new Translation\Translator('en', new Translation\MessageSelector())
);

$container['templating.engine'] = $container->share($container->extend('templating.engine', function($engine, $c) use($templatingHelpers) {
    $engine->setHelpers($templatingHelpers);

    return $engine;
}));
