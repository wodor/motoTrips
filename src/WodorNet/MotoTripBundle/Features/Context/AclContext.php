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
class TripContext extends  BehatContext
{
    /**
     * @Given /^the site has following trips:$/
     */
    public function theSiteHasFollowingTrips(TableNode $table)
    {
        $hash = $table->getHash();
        $em = $this->getEntityManager();
        foreach ($hash as $row) {
            $trip = new Entity\Trip();
            $creator = current($em->getRepository('WodorNetMotoTripBundle:User')->findByUsername($row['creator']));

            $trip->setCreator($creator);
            $trip->setCreationDate(new \DateTime());
            $trip->setStartDate(new \DateTime());
            $trip->setEndDate(new \DateTime("tomorrow"));
            $trip->setLocation(array('lat'=>'10.00', 'lng'=>'20.00'));
            $trip->setTitle($row['title']);
            $trip->setDescription('Lorem ipsum dolor sit amet');
            $trip->setDescriptionPrivate("The very private description");

            $em->persist($trip);

        }
        $em->flush();
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

}
