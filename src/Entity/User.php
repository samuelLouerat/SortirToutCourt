<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\Regex(pattern: "#^[A-z]+$#")]
    private $lastName;

    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\Regex(pattern: "#^[A-z]+$#")]
    private $firstName;

    #[ORM\Column(type: 'string', length: 15)]
    #[Assert\Regex(pattern: "#^0[0-9]{9}+$#")]
    private $phone;

    #[ORM\Column(type: 'boolean',nullable: true)]
    private $admin;

    #[ORM\Column(type: 'boolean',nullable: true)]
    private $active;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Assert\Regex(pattern: "#^\w+$#")]
    private $pseudo;

    #[ORM\OneToMany(mappedBy: 'organizer', targetEntity: Event::class, cascade: ['persist','remove'])]
    private $OrganizedEvent;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'users', cascade: ['persist','remove'])]
    private $isRegistered;

    #[ORM\ManyToOne(targetEntity: Campus::class, cascade: ['persist','remove'], inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\OneToOne(inversedBy: 'user', targetEntity: AvatarFile::class, cascade: ['persist', 'remove'])]
    private $avatarfiles;

    public function __construct()
    {
        $this->OrganizedEvent = new ArrayCollection();
        $this->isRegistered = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin($admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getOrganizedEvent(): Collection
    {
        return $this->OrganizedEvent;
    }

    public function addOrganizedEvent(Event $organizedEvent): self
    {
        if (!$this->OrganizedEvent->contains($organizedEvent)) {
            $this->OrganizedEvent[] = $organizedEvent;
            $organizedEvent->setOrganizer($this);
        }

        return $this;
    }

    public function removeOrganizedEvent(Event $organizedEvent): self
    {
        if ($this->OrganizedEvent->removeElement($organizedEvent)) {
            // set the owning side to null (unless already changed)
            if ($organizedEvent->getOrganizer() === $this) {
                $organizedEvent->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getIsRegistered(): Collection
    {
        return $this->isRegistered;
    }

    public function addIsRegistered(Event $isRegistered): self
    {
        if (!$this->isRegistered->contains($isRegistered)) {
            $this->isRegistered[] = $isRegistered;
        }

        return $this;
    }

    public function removeIsRegistered(Event $isRegistered): self
    {
        $this->isRegistered->removeElement($isRegistered);

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getAvatarfiles(): ?AvatarFile
    {
        return $this->avatarfiles;
    }

    public function setAvatarfiles(?AvatarFile $avatarfiles): self
    {
        $this->avatarfiles = $avatarfiles;

        return $this;
    }

}
