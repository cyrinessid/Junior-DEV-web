<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * Produits
 *
 * @ORM\Table(name="produits", indexes={@ORM\Index(name="test", columns={"id_categorie"}), @ORM\Index(name="id_promotion", columns={"id_promotion"})})
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Produits
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @var string
     *@Assert\NotBlank(message="le nom est obligatoire")
     * @ORM\Column(name="nom", type="string", length=256, nullable=false)
     * @Groups ("post:read")
     */
    private $nom;

    /**
     * @var int
     *@Assert\NotBlank(message="le prix est obligatoire")
     * @ORM\Column(name="prix", type="integer", nullable=true)
     * @Groups ("post:read")
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=256, nullable=false)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *@Assert\NotBlank(message="description est obligatoire")
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "doit etre >=5 ",
     *      maxMessage = "doit etre <=600" )
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Groups ("post:read")
     */
    private $description;

    /**
     * @var int
     *@Assert\NotBlank(message="profit est obligatoire")
     * @ORM\Column(name="profit", type="integer", nullable=false)
     * @Groups ("post:read")
     */
    private $profit;

    /**
     * @var int
     *
     * @ORM\Column(name="prixfinale", type="integer", nullable=false)
     * @Groups ("post:read")
     */
    private $prixfinale;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie",cascade={"persist", "remove" })
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     * })
     * @Groups ("post:read")
     */
    private $idCategorie;

    /**
     * @var \Promotion
     *
     * @ORM\ManyToOne(targetEntity="Promotion",cascade={"persist", "remove" })
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_promotion", referencedColumnName="id")
     * })
     * @Groups ("post:read")
     */
    private $idPromotion;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneCommande", mappedBy="produits")
     */
    private $ligneCommandes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="produits")
     */
    private $utilisateur;


    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

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
    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

        public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProfit(): ?int
    {
        return $this->profit;
    }

    public function setProfit(int $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getPrixfinale(): ?int
    {
        return $this->prixfinale;
    }

    public function setPrixfinale(int $prixfinale): self
    {
        $this->prixfinale = $prixfinale;

        return $this;
    }

    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    public function getIdPromotion() 
    {
        return $this->idPromotion;
    }

    public function setIdPromotion(?Promotion $idPromotion): self
    {
        $this->idPromotion = $idPromotion;

        return $this;
    }

    public function __toString() {
        return $this->getNom();
    }
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }
    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setProduit($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes->removeElement($ligneCommande);
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getProduit() === $this) {
                $ligneCommande->setProduit(null);
            }
        }

        return $this;
    }

}
