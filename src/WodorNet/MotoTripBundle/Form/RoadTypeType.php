<?php

namespace WodorNet\MotoTripBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RoadTypeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('mapIcon')
        ;
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_roadtypetype';
    }
}
