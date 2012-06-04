<?php

use Symfony\Component\Form;

$container['form.factory'] = $container->share(function ($c) {
    $factory = new Form\FormFactory(array(
        new Form\Extension\Core\CoreExtension,
        new Form\Extension\Validator\ValidatorExtension($c['validator']),
    ));

    if (isset($c['form.types'])) {
        foreach ($c['form.types'] as $type) {
            $factory->addType($type);
        }
    }

    return $factory;
});
