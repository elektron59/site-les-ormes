<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MobilHomeRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"nomMh"},
 *  message="Une autre annonce possède déjà ce titre, merci de le modifier"
 * )
 */
class MobilHome
{
    /**
     * Permet d'initialiser le slug avant la création et avant la mise à jour de l'entité
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */

    public function initializeSlug(){
        if(empty($this->slugMh)) { // Si slugMh est vide
            $slugify = new Slugify();
            $this->slugMh = $slugify->slugify($this->nomMh); // transforme en slug nomMh
        }
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(min=10, max=150, minMessage="Le nom doit faire plus de 10 caractères !", maxMessage="Le nom ne peut pas faire plus de 150 caractères")
     */
    private $nomMh;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(min=10, max=150, minMessage="Le nom doit faire plus de 10 caractères !", maxMessage="Le nom ne peut pas faire plus de 150 caractères")
     */
    private $slugMh;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Length( max=20, maxMessage="Le nom de l'emplacement ne peut pas faire plus de 20 caractères")
     */
    private $emplacementMh;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeMh;

    /**
     * @ORM\Column(type="integer")
     */
    private $surfaceMh;

    /**
     * @ORM\Column(type="integer")
     */
    private $surfaceEmplacementMh;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $photoPrincipaleMh;

    /**
     * @ORM\Column(type="float")
     */
    private $tarifJourHsMh;

    /**
     * @ORM\Column(type="float")
     */
    private $tarifSemaineHsMh;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbChambreMh;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPersonneMh;

    /**
     * @ORM\Column(type="float")
     */
    private $tarifJourBsMh;

    /**
     * @ORM\Column(type="float")
     */
    private $tarifSemaineBsMh;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, minMessage="Le détail doit faire plus de 10 caractères !")
     */
    private $detailMh;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="mobilhome", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, minMessage="La présentation doit faire plus de 20 caractères !")
     */
    private $presentationMh;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMh(): ?string
    {
        return $this->nomMh;
    }

    public function setNomMh(string $nomMh): self
    {
        $this->nomMh = $nomMh;

        return $this;
    }

    public function getSlugMh(): ?string
    {
        return $this->slugMh;
    }

    public function setSlugMh(string $slugMh): self
    {
        $this->slugMh = $slugMh;

        return $this;
    }

    public function getEmplacementMh(): ?string
    {
        return $this->emplacementMh;
    }

    public function setEmplacementMh(string $emplacementMh): self
    {
        $this->emplacementMh = $emplacementMh;

        return $this;
    }

    public function getAnneeMh(): ?int
    {
        return $this->anneeMh;
    }

    public function setAnneeMh(?int $anneeMh): self
    {
        $this->anneeMh = $anneeMh;

        return $this;
    }

    public function getSurfaceMh(): ?int
    {
        return $this->surfaceMh;
    }

    public function setSurfaceMh(int $surfaceMh): self
    {
        $this->surfaceMh = $surfaceMh;

        return $this;
    }

    public function getSurfaceEmplacementMh(): ?int
    {
        return $this->surfaceEmplacementMh;
    }

    public function setSurfaceEmplacementMh(int $surfaceEmplacementMh): self
    {
        $this->surfaceEmplacementMh = $surfaceEmplacementMh;

        return $this;
    }

    public function getPhotoPrincipaleMh(): ?string
    {
        return $this->photoPrincipaleMh;
    }

    public function setPhotoPrincipaleMh(string $photoPrincipaleMh): self
    {
        $this->photoPrincipaleMh = $photoPrincipaleMh;

        return $this;
    }

    public function getTarifJourHsMh(): ?float
    {
        return $this->tarifJourHsMh;
    }

    public function setTarifJourHsMh(float $tarifJourHsMh): self
    {
        $this->tarifJourHsMh = $tarifJourHsMh;

        return $this;
    }

    public function getTarifSemaineHsMh(): ?float
    {
        return $this->tarifSemaineHsMh;
    }

    public function setTarifSemaineHsMh(float $tarifSemaineHsMh): self
    {
        $this->tarifSemaineHsMh = $tarifSemaineHsMh;

        return $this;
    }

    public function getNbChambreMh(): ?int
    {
        return $this->nbChambreMh;
    }

    public function setNbChambreMh(int $nbChambreMh): self
    {
        $this->nbChambreMh = $nbChambreMh;

        return $this;
    }

    public function getNbPersonneMh(): ?int
    {
        return $this->nbPersonneMh;
    }

    public function setNbPersonneMh(int $nbPersonneMh): self
    {
        $this->nbPersonneMh = $nbPersonneMh;

        return $this;
    }

    public function getTarifJourBsMh(): ?float
    {
        return $this->tarifJourBsMh;
    }

    public function setTarifJourBsMh(float $tarifJourBsMh): self
    {
        $this->tarifJourBsMh = $tarifJourBsMh;

        return $this;
    }

    public function getTarifSemaineBsMh(): ?float
    {
        return $this->tarifSemaineBsMh;
    }

    public function setTarifSemaineBsMh(float $tarifSemaineBsMh): self
    {
        $this->tarifSemaineBsMh = $tarifSemaineBsMh;

        return $this;
    }

    public function getDetailMh(): ?string
    {
        return $this->detailMh;
    }

    public function setDetailMh(string $detailMh): self
    {
        $this->detailMh = $detailMh;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setMobilhome($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getMobilhome() === $this) {
                $image->setMobilhome(null);
            }
        }

        return $this;
    }

    public function getPresentationMh(): ?string
    {
        return $this->presentationMh;
    }

    public function setPresentationMh(string $presentationMh): self
    {
        $this->presentationMh = $presentationMh;

        return $this;
    }
}
