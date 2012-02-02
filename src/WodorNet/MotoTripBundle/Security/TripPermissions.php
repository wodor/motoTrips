<?php
namespace WodorNet\MotoTripBundle\Security;

class TripPermissions
{
    /**
     * @var \WodorNet\MotoTripBundle\TripSignups\Status
     */
    protected $signupStatus;

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
    function __construct(\Symfony\Component\Security\Core\SecurityContextInterface $securityContext,
                         \Doctrine\ORM\EntityManager $em,
                         \WodorNet\MotoTripBundle\TripSignups\Status $signupStatus)
    {
        $this->securityContext = $securityContext;
        $this->em = $em;

        $this->user = $this->securityContext->getToken()->getUser();
        $this->tripSignupRepository = $this->em->getRepository('\WodorNet\MotoTripBundle\Entity\TripSignup');

        $this->signupStatus = $signupStatus;
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
        $relation = $this->signupStatus->getRelationInfo($trip);

        return $relation=='trip.userrelation.unrelated';
    }

}