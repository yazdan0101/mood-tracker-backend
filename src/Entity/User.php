<?php

namespace App\Entity;


use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_UUID', fields: ['uuid'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $uuid = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, MoodEntry>
     */
    #[ORM\OneToMany(targetEntity: MoodEntry::class, mappedBy: 'user')]
    private Collection $moodEntries;

    public function __construct()

    {
        $this->uuid = Uuid::v4();
        $this->roles = ['ROLE_USER'];
        $this->moodEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, MoodEntry>
     */
    public function getMoodEntries(): Collection
    {
        return $this->moodEntries;
    }

    public function addMoodEntry(MoodEntry $moodEntry): static
    {
        if (!$this->moodEntries->contains($moodEntry)) {
            $this->moodEntries->add($moodEntry);
            $moodEntry->setUser($this);
        }

        return $this;
    }

    public function removeMoodEntry(MoodEntry $moodEntry): static
    {
        if ($this->moodEntries->removeElement($moodEntry)) {
            // set the owning side to null (unless already changed)
            if ($moodEntry->getUser() === $this) {
                $moodEntry->setUser(null);
            }
        }

        return $this;
    }
    public function setUsername(string $username): self
{
    $this->username = $username;
    return $this;
}

public function setPassword(string $password): self
{
    $this->password = $password;
    return $this;
}

}
