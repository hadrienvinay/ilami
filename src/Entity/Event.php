<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="event")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pictures;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Album", mappedBy="event")
     * @ORM\JoinColumn(nullable=true)
     */
    private $album;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventCreated")
     * @ORM\JoinColumn(nullable=true)
     */
    private $creator;
    
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="events")
     */
    private $participants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;
    
     /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    private $latitude;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdDate; 
    
     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate; 
        
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param mixed $pictures
     */
    public function setPictures($pictures): void
    {
        $this->pictures = $pictures;
    }
    
     /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipants(){
        
        return $this->participants;
    }
    
    /**
     * add a participant
     * @param ArrayCollection $participant
     */
    public function addParticipant($participant){
        
         if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addEvent($this);
        }
 
        return $this;
    }
    
    /**
     * remove a participant in an event
     * @param ArrayCollection $participant
     */
    public function removeParticipant($participant){
        
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->removeParticipation($this);
        }    
        
    }
    
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

        /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album): void
    {
        $this->album = $album;
    }

     /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }
    
    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate): void
    {
        $this->createdDate = $createdDate;
    }
    
    /**
     * @return mixed
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
    
    /**
     * @param mixed $updatedDate
     */
    public function setUpdatedDate($updatedDate): void
    {
        $this->updatedDate = $updatedDate;
    }
}
