<?php
namespace WodorNet\MotoTripBundle\Security;

class TripPermissions
{

    /**
     * @var \WodorNet\MotoTripBundle\Entity\TripSignupRepository
     */
    private $tripSignupRepository;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var \WodorNet\MotoTripBundle\Entity\User
     */
    private $user;

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
        $this->tripSignupRepository = $this->em->getRepository('\WodorNet\MotoTripBundle\Entity\TripSignup');

    }


    /**
     * @param \WodorNet\MotoTripBundle\Entity\Trip $trip
     * @return bool
     */
    public function canEdit(\WodorNet\MotoTripBundle\Entity\Trip $trip)
    {
        return $trip->getCreator() === $this->user;
    }

    /**
     * Every approved can view
     * @return bool
     */
    public function canView(\WodorNet\MotoTripBundle\Entity\Trip $trip)
    {
        $tsups = $this->tripSignupRepository->findApprovedByTrip($trip);

        foreach ($tsups->getQuery()->getResult() as $tsup) {
            if ($tsup->getUser() === $this->user) {
                return true;
            }
        }
        return false;
    }

    public function canJoin(\WodorNet\MotoTripBundle\Entity\Trip $trip)
    {
        $tsup = $this->tripSignupRepository->getByTripAndUser($trip, $this->user);
        return !$tsup instanceof \WodorNet\MotoTripBundle\Entity\TripSignup && !$this->canEdit($trip);
    }

}