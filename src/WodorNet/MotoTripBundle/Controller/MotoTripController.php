<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

class MotoTripController extends Controller
{

    public function ensureUserEqualsLoggedIn($userToVerify) {
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if($user !== $userToVerify) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException($user->getUsername(). ' not allowed');
        }
    }
}
