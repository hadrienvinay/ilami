<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="medias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $uploader;  

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;
            
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate; 
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
     public function getUploader(): ?User
    {
        return $this->uploader;
    }

    public function setUploader(?User $uploader): self
    {
        $this->uploader = $uploader;

        return $this;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
    
    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
    
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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
}
