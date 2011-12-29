<?php

namespace WodorNet\MotoTripBundle\Entity;

use WodorNet\MotoTripBundle\Entity\RoadType;
use Doctrine\ORM\Query\AST\Subselect;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WodorNet\MotoTripBundle\Entity\Trip
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WodorNet\MotoTripBundle\Entity\TripRepository")
 */
class Trip
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
     * @var datetime $creationDate
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;


    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var datetime $startDate
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var datetime $endDate
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="TripSignup", mappedBy="trip")
     */
    protected $tripSignups;


    /**
     * @var string $terrainType
     *
     * @ORM\ManyToMany(targetEntity="RoadType", inversedBy="trips")
     * @ORM\JoinTable(name="trips_roadtypes")
     */
    private $roadTypes;


    public function __construct()
    {
        $this->tripSignups = new ArrayCollection();
        $this->roadTypes = new ArrayCollection();
    }


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
     * Set creationDate
     *
     * @param datetime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Get creationDate
     *
     * @return datetime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function addRoadType(RoadType $roadType) {

        $roadType->addTrip($this);
        $this->roadTypes[] = $roadType;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startDate
     *
     * @param datetime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * Get startDate
     *
     * @return datetime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param datetime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * Get endDate
     *
     * @return datetime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    
    public function __toString() {
        return substr($this->getDescription(),0,8);
    }

    /**
     * @return string
     */
    public function getRoadTypes()
    {
        return $this->roadTypes;
    }
}