<?php
namespace WodorNet\MotoTripBundle\Security;

use WodorNet\MotoTripBundle\Security\OwnershipSubscriber;

require_once '/home/wodor/wrk/www/motowypady/src/WodorNet/MotoTripBundle/Security/OwnershipSubscriber.php';

/**
 * Test class for OwnershipSubscriber.
 * Generated by PHPUnit on 2012-01-15 at 14:55:06.
 */
class OwnershipSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OwnershipSubscriber
     */
    protected $object;

    /**
     * @var \Symfony\Component\Security\Acl\Dbal\MutableAclProvider
     */
    protected $aclProvider;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

        $this->aclProvider = $this->getMockBuilder('\Symfony\Component\Security\Acl\Dbal\MutableAclProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new OwnershipSubscriber($this->aclProvider);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function  testPreUpdateAll()
    {

    }

    public function testPreUpdateForOwnerAware()
    {

        $entity = $this->getMock('\WodorNet\MotoTripBundle\Security\OwnerAware');

        $ownerFieldName = 'User';
        $entity->expects($this->any())
            ->method('getOwnerFieldName')
            ->will($this->returnValue($ownerFieldName));

        $securityId = $this->getMockBuilder('\Symfony\Component\Security\Acl\Model\SecurityIdentityInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $entity->expects($this->any())
            ->method('getSecurityIdentity')
            ->will($this->returnValue($securityId));


        $objectId = $this->getMockBuilder('\Symfony\Component\Security\Acl\Model\ObjectIdentityInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $entity->expects($this->any())
            ->method('getObjectIdentity')
            ->will($this->returnValue($objectId));


        $eventArgs = $this->getMockBuilder('\Doctrine\ORM\Event\PreUpdateEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $eventArgs->expects($this->once())
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $eventArgs->expects($this->once())
            ->method('hasChangedField')
            ->will($this->returnValue($ownerFieldName));


        $acl = $this->getMockBuilder('Symfony\Component\Security\Acl\Domain\Acl')
            ->disableOriginalConstructor()
            ->getMock();
        $acl->expects($this->once())
            ->method('insertObjectAce')
            ->with($securityId, \Symfony\Component\Security\Acl\Permission\MaskBuilder::MASK_OWNER);


        $this->aclProvider->expects($this->once())
            ->method('createAcl')
            ->will($this->returnValue($acl));

        $this->aclProvider->expects($this->once())
            ->method('updateAcl')
            ->with($acl);

        $this->object->preUpdate($eventArgs);

    }
}

?>
