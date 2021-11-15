<?php

namespace App\Entity;

use App\Repository\BidRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BidRepository::class)
 */
class Bid
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bid_date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $bid_ammount;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBidDate(): ?\DateTimeInterface
    {
        return $this->bid_date;
    }

    public function setBidDate(\DateTimeInterface $bid_date): self
    {
        $this->bid_date = $bid_date;

        return $this;
    }

    public function getBidAmmount(): ?string
    {
        return $this->bid_ammount;
    }

    public function setBidAmmount(string $bid_ammount): self
    {
        $this->bid_ammount = $bid_ammount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function __toString()
    {
        return $this->getUser() . " : " . $this->getBidAmmount();
    }
}
