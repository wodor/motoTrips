<?php

namespace WodorNet\MotoTripBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Security\OwnerAware;

/**
 * WodorNet\MotoTripBundle\Entity\TripSignup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WodorNet\MotoTripBundle\Entity\TripSignupRepository")
 */
class TripSignup
{

    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_RESIGNED = 'resigned';

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime $signupDate
     *
     * @ORM\Column(name="signupDate", type="datetime")
     */
    private $signupDate;

    /**
     * @var string $signupType
     *
     * @ORM\Column(name="signupType", type="string", length=255)
     */
    private $signupType = 'join';

    /**
     * @var object $trip
     *
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="tripSignups")
     * @ORM\JoinColumn(name="trip", referencedColumnName="id")
     */
    private $trip;

    /**
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tripSignups")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string $description Message to the owner of trip
     *
     * @ORM\Column(type="text")
     *
     */
    private $description;


    /**
     * @var string $description Message to the owner of trip
     *
     * @ORM\Column(type="string", length=20)
     *
     */
    private $status = self::STATUS_NEW;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set signupDate
     *
     * @param datetime $signupDate
     */
    public function setSignupDate($signupDate)
    {
        $this->signupDate = $signupDate;
    }

    /**
     * Get signupDate
     *
     * @return datetime
     */
    public function getSignupDate()
    {
        return $this->signupDate;
    }

    /**
     * Set signupType
     *
     * @param string $signupType
     */
    public function setSignupType($signupType)
    {
        $this->signupType = $signupType;
    }

    /**
     * Get signupType
     *
     * @return string
     */
    public function getSignupType()
    {
        return $this->signupType;
    }

    /**
     * Set trip
     *
     * @param Trip $trip
     */
    public function setTrip(Trip $trip)
    {
        $this->trip = $trip;
        $trip->addTripSignup($this);
    }

    /**
     * Get trip
     *
     * @return Trip
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * Set user
     *
     * @param object $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addTripSignup($this);
    }

    /**
     * Get user
     *
     * @return object
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getOwnerFieldName()
    {
        return 'User';
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getObjectIdentity()
    {
        return ObjectIdentity::fromDomainObject($this);
    }

    public function getSecurityIdentity()
    {
        return UserSecurityIdentity::fromAccount($this->getUser());
    }


}