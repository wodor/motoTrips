<?php
namespace WodorNet\MotoTripBundle\Security;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;

use \WodorNet\MotoTripBundle\Entity\TripSignup;
/**
 * Manages Events commited on trip signups
 */
class TripSignupsSubscriber implements \Doctrine\Common\EventSubscriber {


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
        /**
         * @var $tripSignup \WodorNet\MotoTripBundle\Entity\TripSignup
         */
//        if ($tripSignup instanceof TripSignup) {
//            if ($args->hasChangedField('status')) {
//
//                $this->onStatusChange($args);
//            }
//        }
    }

    /**
     *  @param \Doctrine\ORM\Event\PreUpdateEventArgs $args
     */
    public function onStatusChange( \Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {

        // TODO manage all statuses

        $tripSignup = $args->getEntity();

        // maybe dispatch an tripsignupEvent ?
        $methodName = 'on'.$tripSignup->getStatus().'Status';
        if(method_exists($this, $methodName)) {
            $this->$methodName($args);
        };
    }


    public function onApprovedStatus( \Doctrine\ORM\Event\PreUpdateEventArgs $args) {
        $tripSignup = $args->getEntity();
        $trip = $tripSignup->getTrip();
        $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($trip));
        $securityIdentity = UserSecurityIdentity::fromAccount($tripSignup->getUser());
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_VIEW);
    }

    public function onDissapprovedStatus(\Doctrine\ORM\Event\PreUpdateEventArgs $args) {

        // TODO ususniecie uprawnienia

//        $tripSignup = $args->getEntity();
//        $trip = $tripSignup->getTrip();
//        $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($trip));
//        $securityIdentity = UserSecurityIdentity::fromAccount($tripSignup->getUser());
//        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_VIEW);
    }

    //public function


}