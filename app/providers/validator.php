<?php

use Symfony\Component\Validator;

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
                __DIR__.'/../../vendor/symfony/symfony/src/Symfony/Component/Form/Resources/config/validation.xml')
            )
        ))
    );
});
