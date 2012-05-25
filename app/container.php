<?php

use Symfony\Bundle\FrameworkBundle;
use Symfony\Component\Form;
use Symfony\Component\Templating;
use Symfony\Component\Translation;
use Symfony\Component\Validator;

$container = new Pimple;

$container['validator'] = $container->share(function ($c) {
    return new Validator\Validator(
        $c['validator.mapping.class_metadata_factory'],
        new Validator\ConstraintValidatorFactory
    );
});

$container['validator.mapping.class_metadata_factory'] = $container->share(function ($c) {
    return new Validator\Mapping\ClassMetadataFactory(
        new Validator\Mapping\Loader\LoaderChain(array(
            new Validator\Mapping\Loader\StaticMethodLoader,
            new Validator\Mapping\Loader\XmlFilesLoader(array(
                __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Form/Resources/config/validation.xml')
            )
        ))
    );
});

$container['form.factory'] = $container->share(function ($c) {
    return new Form\FormFactory(array(
        new Form\Extension\Core\CoreExtension,
        new Form\Extension\Validator\ValidatorExtension($c['validator']),
    ));
});

$formTypes = array(
    new Sandbox\Form\Type\GenderType
);

$container['form.factory'] = $container->share($container->extend('form.factory', function($factory, $c) use ($formTypes) {
    foreach ($formTypes as $formType) {
        $factory->addType($formType);
    }
    return $factory;
}));

$container['templating.engine'] = $container->share(function($c) {
    return new Templating\PhpEngine(
        new Sandbox\Templating\TemplateNameParser,
        new Templating\Loader\FilesystemLoader(__DIR__.'/../%name%')
    );
});

// Templating helpers
$templatingHelpers = array();
$templatingHelpers[] = new FrameworkBundle\Templating\Helper\FormHelper($container['templating.engine'], null, array(
    'vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle/Resources/views/Form'
));
$templatingHelpers[] = new FrameworkBundle\Templating\Helper\TranslatorHelper(
    new Translation\Translator('en', new Translation\MessageSelector())
);

$container['templating.engine'] = $container->share($container->extend('templating.engine', function($engine, $c) use($templatingHelpers) {
    $engine->setHelpers($templatingHelpers);

    return $engine;
}));

return $container;