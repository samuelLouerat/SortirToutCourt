<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $startTime;

    #[ORM\Column(type: 'dateinterval')]
    private $duration;

    #[ORM\Column(type: 'datetime')]
    private $registrationTimeLimit;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $registrationMax;

    #[ORM\Column(type: 'string', length: 1500)]
    private $eventInfo;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'OrganizedEvent')]
    #[ORM\JoinColumn(nullable: false)]
    private $organizer;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'isRegistered')]
    private $Users;

    #[ORM\ManyToOne(targetEntity: Campus::class, cascade: ['persist','remove'], inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $campusSite;


    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'events')]

    private $place;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'events')]
    private $state;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationTimeLimit(): ?\DateTimeInterface
    {
        return $this->registrationTimeLimit;
    }

    public function setRegistrationTimeLimit(\DateTimeInterface $registrationTimeLimit): self
    {
        $this->registrationTimeLimit = $registrationTimeLimit;

        return $this;
    }

    public function getRegistrationMax(): ?int
    {
        return $this->registrationMax;
    }

    public function setRegistrationMax(?int $registrationMax): self
    {
        $this->registrationMax = $registrationMax;

        return $this;
    }

    public function getEventInfo(): ?string
    {
        return $this->eventInfo;
    }

    public function setEventInfo(string $eventInfo): self
    {
        $this->eventInfo = $eventInfo;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUserParticipation(User $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users[] = $user;
            $user->addIsRegistered($this);
        }

        return $this;
    }

    public function removeUserParticipation(User $user): self
    {
        if ($this->Users->removeElement($user)) {
            $user->removeIsRegistered($this);
        }

        return $this;
    }

    public function getCampusSite(): ?Campus
    {
        return $this->campusSite;
    }

    public function setCampusSite(?Campus $campusSite): self
    {
        $this->campusSite = $campusSite;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }



    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

}
