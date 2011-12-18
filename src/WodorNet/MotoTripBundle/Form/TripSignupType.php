<?php

namespace WodorNet\MotoTripBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TripSignupType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
           // ->add('signupDate')
           // ->add('signupType')
           // ->add('trip')
          //  ->add('user')
            ->add('testArray')
        ;
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_tripsignuptype';
    }
}
