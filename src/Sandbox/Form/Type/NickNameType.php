<?php

namespace Sandbox\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NickNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', 'text');
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Sandbox\Entity\Nickname',
        );
    }

    public function getName()
    {
        return 'nick_name';
    }
}