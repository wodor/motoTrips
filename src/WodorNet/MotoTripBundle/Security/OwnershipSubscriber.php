<?php
namespace WodorNet\MotoTripBundle\Security;
/**
 * Gives and takes permissions,
 * listens to events from Symfony event dispatcher
 */
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use \Symfony\Component\Security\Acl\Permission\MaskBuilder;
use \Doctrine\ORM\Event\OnFlushEventArgs;

class OwnershipSubscriber implements \Doctrine\Common\EventSubscriber
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
        return array('postPersist');
    }


    /**
     * Keeps acl Owner and field designated to be owner in domain model in sync
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args
     */
    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        /**
         * @var $entity OwnerAware
         */
        $entity = $args->getEntity();

        if ($entity instanceof OwnerAware) {
            $this->ownershipUpdate($entity, $args);
        }
    }

    public function ownershipUpdate($entity)
    {
        //if ($args->hasChangedField($entity->getOwnerFieldName())) {
        // switch to onFlush

        $acl = $this->aclProvider->createAcl($entity->getObjectIdentity(), $entity->getSecurityIdentity());
        $acl->insertObjectAce($entity->getSecurityIdentity(), MaskBuilder::MASK_OWNER);
        $this->aclProvider->updateAcl($acl); // this is untested by current test

        //}
    }

    public function postUpdate()
    {
        // TODO find acl for Previous Owner and delete it
        // `if field changed

    }


    public function preRemove()
    {
        // TODO find acl for  curent Owner and delete it

    }


}