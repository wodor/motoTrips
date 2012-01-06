<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Ivory\GoogleMapBundle\Model\Events\MouseEvent;


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

        $map->setJavascriptVariable('tripsmap');

        $map->setAutoZoom(true);

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('WodorNetMotoTripBundle:Trip')->findUpcomingTrips('10');

        /**
         * @var  \Doctrine\ORM\QueryBuilder $qb 
         */
        foreach ($qb->getQuery()->getResult() as $trip) {
            /**
             * @var  \WodorNet\MotoTripBundle\Entity\Trip $trip
             */
            /**
              * @var $marker \Ivory\GoogleMapBundle\Model\Overlays\Marker
              */
            $marker = $this->get('ivory_google_map.marker');
            $marker->setPosition($trip->getLat(),$trip->getLng());


            $infoWindow = $this->get('ivory_google_map.info_window');
            $infoWindow->setOpenEvent(MouseEvent::CLICK);
            $infoWindow->setContent(htmlspecialchars($trip->getDescription()));

            /**
               * @var $event \Ivory\GoogleMapBundle\Model\Events\Event
               */
            $event = $this->get('ivory_google_map.event');

            $event->setInstance($marker->getJavascriptVariable());
            $event->setEventName('click');
            $event->setHandle('function() {
            '.$infoWindow->getJavascriptVariable().'.open(tripsmap, '.$marker->getJavascriptVariable().');
            if(openedInfoWindow !== undefined) {
                openedInfoWindow.close();
            }
            openedInfoWindow = '.$infoWindow->getJavascriptVariable().';
            }');

            $marker->setOption('title',$trip->getDescription());
            $marker->setInfoWindow($infoWindow);
            $map->addMarker($marker);
            $map->getEventManager()->addDomEvent($event);

        };

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
