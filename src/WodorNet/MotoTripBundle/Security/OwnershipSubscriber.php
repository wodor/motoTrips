<?php
namespace WodorNet\MotoTripBundle\Security;
/**
 * Gives and takes permissions,
 * listens to events from Symfony event dispatcher
 */
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use \Symfony\Component\Security\Acl\Permission\MaskBuilder;
class OwnershipSubscriber implements  \Doctrine\Common\EventSubscriber
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
    public function postPersist(\Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {
        echo "dasd'";exit('ss');
        // ODPALA SIE TYLKO NA UPDATE
        /**
         * @var $entity OwnerAware
         */
        $entity = $args->getEntity();

        if ($entity instanceof OwnerAware) {
            $this->ownershipUpdate($entity, $args);
        }
    }

    public function ownershipUpdate($entity, $args)
    {
        if ($args->hasChangedField($entity->getOwnerFieldName())) {
            // TODO find acl for Previous Owner and delete it

            $acl = $this->aclProvider->createAcl($entity->getObjectIdentity(), $entity->getSecurityIdentity());
            $acl->insertObjectAce($entity->getSecurityIdentity(), MaskBuilder::MASK_OWNER);
            $this->aclProvider->updateAcl($acl);

        }
    }
}