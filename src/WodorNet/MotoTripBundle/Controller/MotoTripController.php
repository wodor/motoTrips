<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

class MotoTripController extends Controller
{
    /**
     * @param $domainObject
     * @param $permissions
     */
    public function grantAccessForCurrentUser($domainObject, $permissions = MaskBuilder::MASK_OWNER)
    { // creating the ACL
        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($domainObject);
        $acl = $aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, $permissions);
        $aclProvider->updateAcl($acl);
    }
}
