<?php
namespace WodorNet\MotoTripBundle\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use WodorNet\MotoTripBundle\MotoTripEvents;
use WodorNet\MotoTripBundle\Event\TripSignupEvent;
use WodorNet\MotoTripBundle\Security\OwnerAware;

/**
 * Gives and takes permissions,
 * listens to events from Symfony event dispatcher
 *
 */
class SymfonySubscriber implements EventSubscriberInterface
{

    /**
     * @var MutableAclProviderInterface
     */
    private $aclProvider;

    public function __construct(MutableAclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
    }


    /**
     * @static
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            MotoTripEvents::onTripSignupApprove => 'onTripSignupApprove',
            MotoTripEvents::onTripSignupDisapprove => 'onTripSignupDisapprove',
            MotoTripEvents::onTripSignupResign => 'onTripSignupResign',
        );
    }

    /**
     * grant VIEW permission to the the trip for the owner of tripsignup
     *
     * @param \WodorNet\MotoTripBundle\Event\TripSignupEvent $event
     */
    public function onTripSignupApprove(TripSignupEvent $event)
    {
        /** @var $acl \Symfony\Component\Security\Acl\Domain\Acl */
      //  $acl = $this->aclProvider->createAcl(ObjectIdentity::fromDomainObject($event->getTripSignup()));
        $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($event->getTripSignup()));
        $securityIdentity = UserSecurityIdentity::fromAccount($event->getTripSignup()->getUser());
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_VIEW);
    }

    /**
     * @param \WodorNet\MotoTripBundle\Event\TripSignupEvent $event
     */
    public function onTripSignupDisapprove(TripSignupEvent $event)
    {
        // przydziel updawnienia

    }


    /**
     * @param \WodorNet\MotoTripBundle\Event\TripSignupEvent $event
     */
    public function onTripSignupResign(TripSignupEvent $event)
    {


    }

}
