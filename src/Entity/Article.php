<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
        const ATTENTE = "En attente";
        const DEMARREE = "En cours";
        const TERMINEE = "Terminée";

        const ETATS_VENTE = [
            "EN ATTENTE"=>self::ATTENTE,
            "DEMARREE"=>self::DEMARREE,
            "TERMINEE"=>self::TERMINEE
        ];
        
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDebutEnchere;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFinEnchere;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $etatVente;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $miseAPrix;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prixVente;

    /**
     * @ORM\OneToMany(targetEntity=Bid::class, mappedBy="article")
     */
    private $bids;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=Pickup::class, mappedBy="article", cascade={"persist", "remove"})
     */
    private $pickup;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebutEnchere(): ?\DateTimeInterface
    {
        return $this->dateDebutEnchere;
    }

    public function setDateDebutEnchere(?\DateTimeInterface $dateDebutEnchere): self
    {
        $this->dateDebutEnchere = $dateDebutEnchere;

        return $this;
    }

    public function getDateFinEnchere(): ?\DateTimeInterface
    {
        return $this->dateFinEnchere;
    }

    public function setDateFinEnchere(?\DateTimeInterface $dateFinEnchere): self
    {
        $this->dateFinEnchere = $dateFinEnchere;

        return $this;
    }

    public function getMiseAPrix(): ?float
    {
        return $this->miseAPrix;
    }

    public function setMiseAPrix(?float $miseAPrix): self
    {
        $this->miseAPrix = $miseAPrix;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(?float $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getEtatVente(): ?string
    {
        return $this->etatVente;
    }

    public function setEtatVente(string $etatVente): self
    {
        $this->etatVente = $etatVente;

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setArticle($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getArticle() === $this) {
                $bid->setArticle(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPickup(): ?Pickup
    {
        return $this->pickup;
    }

    public function setPickup(?Pickup $pickup): self
    {
        // unset the owning side of the relation if necessary
        if ($pickup === null && $this->pickup !== null) {
            $this->pickup->setArticle(null);
        }

        // set the owning side of the relation if necessary
        if ($pickup !== null && $pickup->getArticle() !== $this) {
            $pickup->setArticle($this);
        }

        $this->pickup = $pickup;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
}
