<?php

namespace WodorNet\MotoTripBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TripType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('emails');
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_triptype';
    }

}
