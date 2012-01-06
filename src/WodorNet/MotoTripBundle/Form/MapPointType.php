<?php

namespace WodorNet\MotoTripBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Ivory\GoogleMapBundle\Model\Overlays\Animation;

use Ivory\GoogleMapBundle\Model\Map;
use Ivory\GoogleMapBundle\Model\Overlays\Marker;
use Ivory\GoogleMapBundle\Model\Events\Event;

class MapPointType extends AbstractType {

    /**
     * @var $map \Ivory\GoogleMapBundle\Model\Map
     */
    protected $map;

    /**
     * @var $marker \Ivory\GoogleMapBundle\Model\Overlays\Marker
     */
    protected $marker;

    /**
     * @var $event \Ivory\GoogleMapBundle\Model\Events\Event
     */
    protected $event;

    public function __construct(Map $map, Event $event, Marker $marker) {
        $this->map = $map;
        $this->event = $event;
        $this->marker = $marker;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'mapPoint';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('lat','hidden', array(
            'attr' =>  array('class' => $builder->getName().'lat'),
        ))
        ->add('lng','hidden', array(
            'attr' =>  array('class' => $builder->getName().'lng'),
        ));
    }


    /**
     * {@inheritdoc}
     */
    function buildView(FormView $view, FormInterface $form)
    {
        $value = $view->get('value');
        $showMarker = !is_null($value['lat']) && !is_null($value['lng']);

        $this->map = $this->map;

        if($showMarker) {
            $this->map->setCenter((float)$value['lat'], (float)$value['lng'], true);
            $this->map->setMapOption('zoom', 10);
        }

        $this->map->setJavascriptVariable('map');

        $this->event->setInstance($this->map->getJavascriptVariable());
        $this->event->setEventName('click');
        $this->event->setHandle('setTripMarker');


        $this->marker->setJavascriptVariable('marker_trip');
        $this->marker->setPosition((float)$value['lat'], (float)$value['lng'], true);
        $this->marker->setAnimation(Animation::DROP);
        $this->marker->setOption('draggable', true);
        $this->marker->setOption('visible', $showMarker);

        $this->map->getEventManager()->addDomEvent($this->event);
        $this->map->addMarker($this->marker);

        $view->set('map', $this->map);
    }
}