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
     * @Given /^the "([^"]*)" user has "([^"]*)" description$/
     */
    public function theUserHasDescription($userName, $description)
    {
        $em = $this->getEntityManager();
        /** @var $user \WodorNet\MotoTripBundle\Entity\User */
        $user = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($userName));

        $user->setDescription($description);
        $em->persist($user);
        $em->flush();

    }


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
        $trip = current($em->getRepository('WodorNetMotoTripBundle:Trip')->findByTitle($tripTitle));
        foreach ($hash as $row) {
            $user = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($row['user']));
            $tripSignup = new \WodorNet\MotoTripBundle\Entity\TripSignup();
            $tripSignup->setTrip($trip);
            $tripSignup->setUser($user);
            $tripSignup->setStatus($row['status']);
            $tripSignup->setSignupDate(new \DateTime());

            !isset($row['message']) AND $row['message'] = 'Mane! tekel! fares!';
            $tripSignup->setDescription($row['message']);

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

        $startDate = new \DateTime();

        $startDate->add(new \DateInterval('P'.rand(0,200).'D'));
        $endDate = clone $startDate;
        $endDate->add(new \DateInterval('PT'.rand(3,48).'H'.rand(0,60).'M'));

        $trip = new Entity\Trip();
        $trip->setCreator($creator);
        $trip->setCreationDate(new \DateTime());
        $trip->setStartDate($startDate);
        $trip->setEndDate($endDate);
        $trip->setLocation(array('lat' => '21.00', 'lng' => '52.00'));
        $trip->setTitle($data['title']);
        $trip->setDescription($data['description']);
        $trip->setDescriptionPrivate($data['descritpion_private']);
        return $trip;
    }


}
