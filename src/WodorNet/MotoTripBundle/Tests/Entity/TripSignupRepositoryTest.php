<?php
namespace WodorNet\MotoTripsBundle\Tests\Entity;
use Doctrine\Tests\OrmTestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\DriverChain;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

class TripSignupRepositoryTest extends OrmTestCase
{
    private $_em;

    protected function setUp()
    {
        $reader = new AnnotationReader();
        $reader->setIgnoreNotImportedAnnotations(true);
        /** @noinspection PhpDeprecationInspection */
        $reader->setEnableParsePhpImports(true);

        $metadataDriver = new AnnotationDriver(
            $reader,
            // provide the namespace of the entities you want to tests
            'WodorNet\\MotoTripBundle\\Entity'
        );

        $this->_em = $this->_getTestEntityManager();

        $this->_em->getConfiguration()
            ->setMetadataDriverImpl($metadataDriver);

        // allows you to use the AcmeProductBundle:Product syntax
        $this->_em->getConfiguration()->setEntityNamespaces(array(
            'WodorNetMotoTripBundle' => 'WodorNet\\MotoTripBundle\\Entity'
        ));
    }

    public function testGetByTripAndUser()
    {

        //        $tc = $this;
        //        /** @var $tc \PHPUnit_Framework_TestCase */
        //        $trip = $this->getMock('WodorNet\MotoTripBundle\Entity\Trip');
        //        $trip->expects($this->any())->method('getId()')->will($this->returnValue(1));
        //
        //        $user = $tc->getMock('WodorNet\MotoTripBundle\Entity\User');
        //        $trip->expects($this->any())->method('getId()')->will($this->returnValue(1));
        //
        ////        $trip = new \WodorNet\MotoTripBundle\Entity\Trip();
        ////        $user = new \WodorNet\MotoTripBundle\Entity\User();
        //
        //        $queryBuilder = $this->_em->getRepository('WodorNetMotoTripBundle:TripSignup')
        //            ->getByTripAndUser($trip, $user, \WodorNet\MotoTripBundle\Entity\TripSignup::STATUS_REJECTED);
        //
        //        $tc->assertEquals('p.name LIKE :name', (string) $queryBuilder->getDqlPart('where'));
        //        $tc->assertEquals(array('name' => 'foo'), $queryBuilder->getParameters());

    }

}
