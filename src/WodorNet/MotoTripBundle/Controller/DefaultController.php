<?php

namespace WodorNet\MotoTripBundle\Controller;

use WodorNet\MotoTripBundle\Controller\MotoTripController as Controller;
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
        return array();
    }

    /**
     * @Route("/userbar", name="default2")
     * @Template()
     */
    public function userBarAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        return array(
            'user' => $user,
            'csrf_token' => $csrfToken,
        );
    }

    /**
     * @Route("/map", name="all_trips_map")
     * @Template()
     */
    public function allTripsMapAction()
    {
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
            $marker->setPosition($trip->getLat(), $trip->getLng());

            $infoWindow = $this->get('ivory_google_map.info_window');
            $infoWindow->setOpenEvent(MouseEvent::CLICK);
            $infoWindow->setAutoClose(true);

            $infoWindowContent = $this->forward('WodorNetMotoTripBundle:Trip:infoWindow', array(
                'trip' => $trip,
            ));

            $infoWindow->setContent($infoWindowContent->getContent());

            /**
             * @var $event \Ivory\GoogleMapBundle\Model\Events\Event
             */
            $event = $this->get('ivory_google_map.event');

            $event->setInstance($marker->getJavascriptVariable());
            $event->setEventName('click');

            $marker->setOption('title', $trip->getTitle());
            $marker->setInfoWindow($infoWindow);
            $map->addMarker($marker);

        }
        ;

        return array('map' => $map);
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
