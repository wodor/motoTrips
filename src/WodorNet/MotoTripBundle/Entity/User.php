<?php
namespace WodorNet\MotoTripBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TripSignup", mappedBy="user")
     */
    protected $tripSignups;


    /**
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="creator")
     */
    protected $trips;


    public function __construct()
    {
        parent::__construct();
        $this->tripSignups = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->username;
    }


}