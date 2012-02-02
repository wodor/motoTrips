<?php

namespace WodorNet\MotoTripBundle\TripSignups;

use \WodorNet\MotoTripBundle\Entity\TripSignup;
use \WodorNet\MotoTripBundle\Event\TripSignupEvent;
use \WodorNet\MotoTripBundle\MotoTripEvents;

use \Symfony\Component\EventDispatcher\EventDispatcher;

class Manager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $dispatcher;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, EventDispatcher $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Move from candidates to attendees by trip owner
     * @param \WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup
     */
    public function approve(TripSignup $tripSignup)
    {
        $tripSignup->setStatus(TripSignup::STATUS_APPROVED);
        $this->em->persist($tripSignup);
        $this->em->flush();

        $event = new TripSignupEvent($tripSignup);
        $this->dispatcher->dispatch(MotoTripEvents::onTripSignupApprove, $event);
    }

    /**
     * Move from attendees back to candidates by trip owner
     * @param \WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup
     */
    public function disApprove(TripSignup $tripSignup)
    {
        $tripSignup->setStatus(TripSignup::STATUS_NEW);
        $this->em->persist($tripSignup);
        $this->em->flush();

        $event = new TripSignupEvent($tripSignup);
        $this->dispatcher->dispatch(MotoTripEvents::onTripSignupDisapprove, $event);
    }

    /**
     * remove from candidates by trip owner
     * @param \WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup
     */
    public function reject(TripSignup $tripSignup)
    {
        $tripSignup->setStatus(TripSignup::STATUS_REJECTED);
        $this->em->persist($tripSignup);
        $this->em->flush();

        $event = new TripSignupEvent($tripSignup);
        $this->dispatcher->dispatch(MotoTripEvents::onTripSignupReject, $event);
    }

    /**
     * Remove from candidates or attendees by attendee
     * mail is sent in other direction
     * @param \WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup
     */
    public function resign(TripSignup $tripSignup)
    {

        $tripSignup->setStatus(TripSignup::STATUS_RESIGNED);
        $this->em->persist($tripSignup);
        $this->em->flush();

        $event = new TripSignupEvent($tripSignup);
        $this->dispatcher->dispatch(MotoTripEvents::onTripSignupResign, $event);
    }




}
