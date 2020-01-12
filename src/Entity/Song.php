<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 */
class Song
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
        
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="songs")
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
    private $artist;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $album;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate; 

    public function getId(): ?int
    {
        return $this->id;
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

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

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

    public function getUploader(): ?User
    {
        return $this->uploader;
    }

    public function setUploader(?User $uploader): self
    {
        $this->uploader = $uploader;

        return $this;
    }
}
