<?php


namespace WodorNet\MotoTripBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext,
Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
Behat\Behat\Context\TranslatedContextInterface,
Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;

use WodorNet\MotoTripBundle\Entity;

/**
 * Feature context.
 */
class TripContext extends BehatContext
{
    /**
     * @Given /^the site has following trips:$/
     */
    public function theSiteHasFollowingTrips(TableNode $table)
    {
        $hash = $table->getHash();
        $em = $this->getEntityManager();
        foreach ($hash as $row) {
            $creator = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($row['creator']));
            $trip = $this->getTrip($creator, $row);
            $em->persist($trip);

        }
        $em->flush();
    }

    /**
     * @Given /^the "([^"]*)" trip has the following signups:$/
     */
    public function theTripHasTheFollowingSignups($tripTitle, TableNode $table)
    {

        $hash = $table->getHash();
        $em = $this->getEntityManager();
        echo "\n\n" . $tripTitle;
        $trip = current($em->getRepository('WodorNetMotoTripBundle:Trip')->findByTitle($tripTitle));
        foreach ($hash as $row) {
            $user = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($row['user']));
            $tripSignup = new \WodorNet\MotoTripBundle\Entity\TripSignup();
            $tripSignup->setTrip($trip);
            $tripSignup->setUser($user);
            $tripSignup->setStatus($row['status']);
            $tripSignup->setSignupDate(new \DateTime());
            $tripSignup->setDescription('Mane tekel fares');

            $em->persist($tripSignup);

        }
        $em->flush();
    }

    /**
     * @When /^signup of "([^"]*)" for "([^"]*)" is approved$/
     */
    public function signupOfForIsApproved($userName, $tripTitle)
    {
        $em = $this->getEntityManager();
        $candidate = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($userName));
        $trip = current($em->getRepository('WodorNetMotoTripBundle:Trip')->findByTitle($tripTitle));

        $manager = $this->getContainer()->get('wodor_net_moto_trip.tripsignups');

        $tripSignup = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->getByTripAndUser($trip, $candidate);
        $manager->approve($tripSignup);
    }


    /**
     * @Given /^User "([^"]*)" should be in trip candiates for trip "([^"]*)"$/
     */
    public function UserShouldBeInTripCandiatesOf($userName, $tripId)
    {
        $em = $this->getEntityManager();
        $candidate = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($userName));
        $trip = current($em->getRepository('WodorNetMotoTripBundle:Trip')->findById($tripId));

        return $candidate->isCandidateForTrip($trip);
    }


    /**
     * Returns entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @Given /^I edit trip "([^"]*)"$/
     */
    public function iEditTrip($title)
    {
        $trip = current($this->getEntityManager()->getRepository('WodorNetMotoTripBundle:Trip')->findByTitle($title));

        $trip->setDescription($title . " desc ");
        $this->getEntityManager()->persist($trip);
        $this->getEntityManager()->flush();
    }

    protected function getTrip($creator, $data)
    {
        $trip = new Entity\Trip();
        $trip->setCreator($creator);
        $trip->setCreationDate(new \DateTime());
        $trip->setStartDate(new \DateTime());
        $trip->setEndDate(new \DateTime("tomorrow"));
        $trip->setLocation(array('lat' => '10.00', 'lng' => '20.00'));
        $trip->setTitle($data['title']);
        $trip->setDescription($data['description']);
        $trip->setDescriptionPrivate($data['descritpion_private']);
        return $trip;
    }


}
