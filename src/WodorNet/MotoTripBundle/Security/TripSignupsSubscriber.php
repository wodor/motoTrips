<?php
namespace WodorNet\MotoTripBundle\Security;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


use \Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use \WodorNet\MotoTripBundle\Entity\TripSignup;

/**
 * Manages Events commited on trip signups
 */
class TripSignupsSubscriber implements \Doctrine\Common\EventSubscriber
{


    /**
     * @var MutableAclProviderInterface
     */
    private $aclProvider;

    public function __construct(MutableAclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
    }

    public function getSubscribedEvents()
    {
        return array('preUpdate');
    }

    /**
     * grant VIEW permission to the the trip for the owner of tripsignup
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args
     */
    public function preUpdate(\Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {
        $tripSignup = $args->getEntity();
        /**
         * @var $tripSignup \WodorNet\MotoTripBundle\Entity\TripSignup
         */
        if ($tripSignup instanceof TripSignup) {
            if ($args->hasChangedField('status')) {

                $this->onStatusChange($args);
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args
     */
    public function onStatusChange(\Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {
        $tripSignup = $args->getEntity();

        // maybe dispatch an tripsignupEvent ?
        $methodName = 'on' . $tripSignup->getStatus() . 'Status';
        if (method_exists($this, $methodName)) {
            $this->$methodName($args);
        }
        ;
    }


    public function onApprovedStatus(\Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {
        $tripSignup = $args->getEntity();
        $trip = $tripSignup->getTrip();

        $oi = new ObjectIdentity($trip->getId(), 'WodorNet\MotoTripBundle\Entity\Trip');

        $acl = $this->aclProvider->findAcl($oi);
        $securityIdentity = new UserSecurityIdentity($tripSignup->getUser()->getUserName(), 'WodorNet\MotoTripBundle\Entity\User');
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_VIEW);
        $this->aclProvider->updateAcl($acl);


    }

    public function onDissapprovedStatus(\Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {

        // TODO ususniecie uprawnienia

        //        $tripSignup = $args->getEntity();
        //        $trip = $tripSignup->getTrip();
        //        $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($trip));
        //        $securityIdentity = UserSecurityIdentity::fromAccount($tripSignup->getUser());
        //        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_VIEW);
    }

    //public function


}