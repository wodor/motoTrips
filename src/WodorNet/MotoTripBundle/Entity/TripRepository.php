<?php

namespace WodorNet\MotoTripBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TripRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TripRepository extends EntityRepository
{

    public function findUpcomingTrips() {

        $qb = $this->createQueryBuilder('t');

        $qb->add('where',  $qb->expr()->gt('t.startDate', ':now'));
        $qb->add('orderBy', "t.startDate asc");
        
        $qb->setParameter('now', new \DateTime('midnight today'));
        return $qb;
        
    }


}