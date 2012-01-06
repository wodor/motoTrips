<?php

namespace WodorNet\MotoTripBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TripType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder
           // ->add('terrainType', 'choice',$terrainTypeOptions)
            ->add('description')
            ->add('startDate','datepicker')
            ->add('endDate','datepicker')
            ->add('roadTypes', null, array('expanded'=>true, 'csrf_protection'=>false))
            ->add('location','mapPoint')
        ;
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_triptype';
    }

}
