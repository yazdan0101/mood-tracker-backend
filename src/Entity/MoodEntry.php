<?php

namespace App\Entity;

use App\Repository\MoodEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoodEntryRepository::class)]
class MoodEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $moodType = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $occurredAt = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $feelingList = [];

    #[ORM\Column(length: 255)]
    private ?string $sleepQuality = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $activityList = [];

    #[ORM\Column(length: 255)]
    private ?string $bestAboutToday = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\ManyToOne(inversedBy: 'moodEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoodType(): ?string
    {
        return $this->moodType;
    }

    public function setMoodType(string $moodType): static
    {
        $this->moodType = $moodType;

        return $this;
    }

    public function getOccurredAt(): ?\DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function setOccurredAt(\DateTimeImmutable $occurredAt): static
    {
        $this->occurredAt = $occurredAt;

        return $this;
    }

    public function getFeelingList(): array
    {
        return $this->feelingList;
    }

    public function setFeelingList(array $feelingList): static
    {
        $this->feelingList = $feelingList;

        return $this;
    }

    public function getSleepQuality(): ?string
    {
        return $this->sleepQuality;
    }

    public function setSleepQuality(string $sleepQuality): static
    {
        $this->sleepQuality = $sleepQuality;

        return $this;
    }

    public function getActivityList(): array
    {
        return $this->activityList;
    }

    public function setActivityList(array $activityList): static
    {
        $this->activityList = $activityList;

        return $this;
    }

    public function getBestAboutToday(): ?string
    {
        return $this->bestAboutToday;
    }

    public function setBestAboutToday(string $bestAboutToday): static
    {
        $this->bestAboutToday = $bestAboutToday;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
