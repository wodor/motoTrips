<?php

namespace WodorNet\MotoTripBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TripType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            //->add('creationDate')
            ->add('terrainType')
            ->add('description')
            ->add('startDate', null, array('widget' => 'single_text'))
            ->add('endDate', null, array('widget' => 'single_text'))
        ;
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_triptype';
    }
}
