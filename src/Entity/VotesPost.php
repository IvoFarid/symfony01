<?php

namespace App\Entity;

use App\Repository\VotesPostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotesPostRepository::class)]
class VotesPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $postId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\Column]
    private ?bool $voted = null;

    #[ORM\Column(nullable: true)]
    private ?bool $direction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?Post
    {
        return $this->postId;
    }

    public function setPostId(?Post $postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function isVoted(): ?bool
    {
        return $this->voted;
    }

    public function setVoted(bool $voted): self
    {
        $this->voted = $voted;

        return $this;
    }

    public function __toString()
    {
        return 'this';
    }

    public function isDirection(): ?bool
    {
        return $this->direction;
    }

    public function setDirection(?bool $direction): self
    {
        $this->direction = $direction;

        return $this;
    }
}
