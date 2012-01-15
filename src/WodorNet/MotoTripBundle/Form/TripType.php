<?php

namespace WodorNet\MotoTripBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TripType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('attr' => array('class' => 'title')))
            ->add('description', 'textarea', array('attr' => array('class' => 'big')))
            ->add('descriptionPrivate', 'textarea', array('attr' => array('class' => 'big')))
            ->add('startDate', 'datepicker')
            ->add('endDate', 'datepicker')
            ->add('roadTypes', null, array('expanded' => true, 'csrf_protection' => false, 'attr' => array('class' => 'normalFont')))
            ->add('location', 'mapPoint');
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_triptype';
    }

}
