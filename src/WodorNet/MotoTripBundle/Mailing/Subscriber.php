<?php
namespace WodorNet\MotoTripBundle\Mailing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WodorNet\MotoTripBundle\MotoTripEvents;

class Subscriber implements EventSubscriberInterface
{

    static public function getSubscribedEvents()
    {
        return array(
            MotoTripEvents::onTripSignupApprove => 'onTripSignupApprove',
        );
    }


    public function onTripSignupApprove() {

        exit('hurray');

    }

}
