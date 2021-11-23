<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Vich\Uploadable
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

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $image;

    /**
     * @var File
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_bid_value;

    public function __construct()
    {
        $this->max_bid_value = 0;
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

    public function getUser(): ?User
    {
        return $this->user;
    }
    
    public function setUser(?User $user): self
    {
        $this->user = $user;
        
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }
    
    public function __toString()
    {
        return $this->getName();
    }

    public function getMaxBidValue(): ?int
    {
        return $this->max_bid_value;
    }

    public function setMaxBidValue(?int $max_bid_value): self
    {
        $this->max_bid_value = $max_bid_value;

        return $this;
    }
}
