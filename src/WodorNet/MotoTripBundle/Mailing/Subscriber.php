<?php
namespace WodorNet\MotoTripBundle\Mailing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WodorNet\MotoTripBundle\Event\TripSignupEvent;
use WodorNet\MotoTripBundle\MotoTripEvents;
use WodorNet\MotoTripBundle\Mailing\Sender;

/**
 * i have noticed that it's better (for performace)  to pass whole service container to event subscriber, than an defined
 * service with dependecies like mailer or translator, baecause this way we loose lazy load
 * < Stof> wodor: no, subscribers are registered lazily too
 * < Stof> well, in 2.1
 */
class Subscriber implements EventSubscriberInterface
{
    /**
     * @var \WodorNet\MotoTripBundle\Mailing\Sender
     */
    public $sender;

    /**
     * @param \WodorNet\MotoTripBundle\Mailing\Sender $sender
     */
    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @static
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            MotoTripEvents::onTripSignupApprove => 'onTripSignupApprove',
        );
    }

    /**
     * @param \WodorNet\MotoTripBundle\Event\TripSignupEvent $event
     */
    public function onTripSignupApprove(TripSignupEvent $event)
    {
        return $this->sender->sendSingnupApprove($event->getTripSignup());
    }

}
