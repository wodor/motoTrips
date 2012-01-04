<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'dfupa');
    }

    /**
     * @Route("/map", name="all_trips_map")
     * @Template()
     */
    public function allTripsMapAction(){
        $map = $this->get('ivory_google_map.map');

        return array('map'=>$map);
    }

    /**
     * @Route("/any")
     * @Template()
     */
    public function anyAction($name)
    {
        return array('name' => $name);
    }


}
