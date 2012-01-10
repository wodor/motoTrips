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
            ->add('description', 'textarea', array('attr' => array('class' => 'big')));
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_tripsignuptype';
    }
}
