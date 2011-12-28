<?php

namespace WodorNet\MotoTripBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TripType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {

        $terrainTypeOptions['expanded'] = true;
        $terrainTypeOptions['multiple'] = true;
        $terrainTypeOptions['choices'] = array(
            1 => 'tor crossowy',
            2 => 'drogi nieutwardzone',
            3 => 'asfalt',
            4 => 'tor asfaltowy'
        );

        $builder
           // ->add('terrainType', 'choice',$terrainTypeOptions)
            ->add('description')
            ->add('startDate','datepicker')
            ->add('endDate','datepicker')
        ;
    }

    public function getName()
    {
        return 'wodornet_mototripbundle_triptype';
    }
}
