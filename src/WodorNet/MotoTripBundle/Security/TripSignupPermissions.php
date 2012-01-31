<?php

namespace WodorNet\MotoTripBundle\Security;


class TripSignupPermissions {

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var \WodorNet\MotoTripBundle\Entity\User
     */
    private  $user;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    function __construct(\Symfony\Component\Security\Core\SecurityContextInterface $securityContext, \Doctrine\ORM\EntityManager $em)
    {
        $this->securityContext = $securityContext;
        $this->em = $em;

        $this->user = $this->securityContext->getToken()->getUser();
    }

    public function canEdit(\WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup) {
        return $this->user === $tripSignup->getUser();
    }

    public function canView() {
        return true;
    }
}
