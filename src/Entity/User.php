<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Notifiable(name="user")
 */
class User extends BaseUser implements NotifiableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ProfilePicture", inversedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $profilePicture;
    
     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="publisher")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pictures;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Job", inversedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $job;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="creator", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $eventCreated;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Song", mappedBy="uploader", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $songs;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Album", mappedBy="creator", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $albumCreated;
    
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", inversedBy="participants", cascade={"persist", "remove"})
     */
    private $events;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recommandation", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $recommandations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prename;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $team;
    
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;
    
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate;
    
     public function __construct()
    {
        $this->roles = array();
        $this->events = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->eventCreated = new ArrayCollection();
        $this->albumCreated = new ArrayCollection();
        $this->recommandations = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param mixed $profilePicture
     */
    public function setProfilePicture($profilePicture): void
    {
        $this->profilePicture = $profilePicture;
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
    
    public function addPicture($picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }
    
     /**
     * @return mixed
     */
    public function getEventCreated()
    {
        return $this->eventCreated;
    }

    /**
     * @param mixed $eventCreated
     */
    public function setEventCreated($eventCreated): void
    {
        $this->eventCreated = $eventCreated;
    }
    
     /**
     * @return mixed
     */
    public function getAlbumCreated()
    {
        return $this->albumCreated;
    }

    /**
     * @param mixed $albumCreated
     */
    public function setAlbumCreated($albumCreated): void
    {
        $this->albumCreated = $albumCreated;
    }
    
    /**
     * @return mixed
     */
    public function getRecommandations()
    {
        return $this->recommandations;
    }

    /**
     * @param mixed $recommandation
     */
    public function setRecommandation($recommandation): void
    {
        $this->recommandations = $recommandation;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getEvents(){
        
        return $this->events;
    }
    
        
    /**
     * Add a event participation
     * @param ArrayCollection $event
     */
    public function addEvent($event){
        
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addParticipant($this);
        }
 
        return $this;    
        
    }
    
    /**
     * remove participation to an event
     * @param ArrayCollection $event
     */
    public function removeParticipation($event){
        
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeParticipant($this);
        }    
        
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     */
    public function setJob($job): void
    {
        $this->job = $job;
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

    public function getPrename(): ?string
    {
        return $this->prename;
    }

    public function setPrename(string $prename): self
    {
        $this->prename = $prename;

        return $this;
    }
    
        public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
    {
        $this->team = $team;

        return $this;
    }
    
        public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    
        public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
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

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getPublisher() === $this) {
                $picture->setPublisher(null);
            }
        }

        return $this;
    }

    public function addEventCreated(Event $eventCreated): self
    {
        if (!$this->eventCreated->contains($eventCreated)) {
            $this->eventCreated[] = $eventCreated;
            $eventCreated->setCreator($this);
        }

        return $this;
    }

    public function removeEventCreated(Event $eventCreated): self
    {
        if ($this->eventCreated->contains($eventCreated)) {
            $this->eventCreated->removeElement($eventCreated);
            // set the owning side to null (unless already changed)
            if ($eventCreated->getCreator() === $this) {
                $eventCreated->setCreator(null);
            }
        }

        return $this;
    }

    public function addAlbumCreated(Album $albumCreated): self
    {
        if (!$this->albumCreated->contains($albumCreated)) {
            $this->albumCreated[] = $albumCreated;
            $albumCreated->setCreator($this);
        }

        return $this;
    }

    public function removeAlbumCreated(Album $albumCreated): self
    {
        if ($this->albumCreated->contains($albumCreated)) {
            $this->albumCreated->removeElement($albumCreated);
            // set the owning side to null (unless already changed)
            if ($albumCreated->getCreator() === $this) {
                $albumCreated->setCreator(null);
            }
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
        }

        return $this;
    }

    public function addRecommandation(Recommandation $recommandation): self
    {
        if (!$this->recommandations->contains($recommandation)) {
            $this->recommandations[] = $recommandation;
            $recommandation->setUser($this);
        }

        return $this;
    }

    public function removeRecommandation(Recommandation $recommandation): self
    {
        if ($this->recommandations->contains($recommandation)) {
            $this->recommandations->removeElement($recommandation);
            // set the owning side to null (unless already changed)
            if ($recommandation->getUser() === $this) {
                $recommandation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Song[]
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->setUploader($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->contains($song)) {
            $this->songs->removeElement($song);
            // set the owning side to null (unless already changed)
            if ($song->getUploader() === $this) {
                $song->setUploader(null);
            }
        }

        return $this;
    }
}
