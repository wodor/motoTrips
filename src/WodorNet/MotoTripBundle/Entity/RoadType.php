<?php

namespace WodorNet\MotoTripBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WodorNet\MotoTripBundle\Entity\Trip;


/**
 * WodorNet\MotoTripBundle\Entity\RoadType
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WodorNet\MotoTripBundle\Entity\RoadTypeRepository")
 */
class RoadType
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Trip", mappedBy="roadTypes")
     */
    private $trips;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function addTrip(Trip $trip) {
        $this->trips[] = $trip;
    }
}