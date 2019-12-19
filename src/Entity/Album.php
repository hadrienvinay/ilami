<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="album")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pictures;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Event", inversedBy="album")
     * @ORM\JoinColumn(nullable=true)
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $presentationPicture;

        /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate; 
        
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
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event): void
    {
        $this->event = $event;
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
    
        public function getPresentationPicture(): ?string
    {
        return $this->presentationPicture;
    }

    public function setPresentationPicture(string $presentationPicture): self
    {
        $this->presentationPicture = $presentationPicture;

        return $this;
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
