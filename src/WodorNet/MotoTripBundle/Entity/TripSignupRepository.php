<?php

namespace WodorNet\MotoTripBundle\Entity;

use Doctrine\ORM\EntityRepository,
 WodorNet\MotoTripBundle\Entity\Trip;
/**
 * TripSignupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TripSignupRepository extends EntityRepository
{


    private function findByTrip(Trip $trip){

        $qb = $this->createQueryBuilder('ts');

        $where = $qb->expr()->andx(
            $qb->expr()->eq('ts.status', ':status'),
            $qb->expr()->eq('ts.trip', ':trip')
        );

        $qb->add('where',  $where);

        $qb->add('orderBy', "ts.signupDate asc");

        $qb->setParameter('trip', $trip);

        return $qb;

    }

    public function findCandidatesByTrip(Trip $trip) {

        $qb = $this->findByTrip($trip);
        $qb->setParameter('status', 'new');
        return $qb;

    }

    public function findApprovedByTrip(Trip $trip) {

        $qb = $this->findByTrip($trip);
        $qb->setParameter('status', 'approved');

        return $qb;

    }

}