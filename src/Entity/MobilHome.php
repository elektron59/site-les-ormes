<?php

namespace App\Entity;

use App\Entity\User;
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="mobilHomes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="annonce")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="ad", orphanRemoval=true)
     */
    private $comments;

    

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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
     * Permet de récupérer le commentaire d'un auteur par rapport à une annonce
     *
     * @param User $author
     * @return Comment|null
     */
    public function getCommentFromAuthor(User $author){
        foreach($this->comments as $comment){
            if($comment->getAuthor() === $author) return $comment;
        }

        return null;
    }

    /**
     * Permet d'obtenir la moyenne globale des nots pour cette annonce
     *
     * @return float
     */
    public function getAvgRatings() {
        //Calculer la somme des notations
        $sum = array_reduce($this->comments->toArray(), function($total, $comment){
            return $total + $comment->getRating();
        },0);

        // Faire la division pour avoir la moyenne
        if(count($this->comments) > 0) return $sum / count($this->comments);
    }

    /**
     * Permet d'obtenir un tableau des jours qui ne sont pas disponibles pour cette annonce
     *
     * @return array Un tableau d'objets DateTime représentant les jours d'occupation
     */
    public function getNotAvailableDays() {
        $notAvailableDays = [];// Tableau qui contiendra l'ensemble des date déjà réservées

        foreach ($this->bookings as $booking) {
            // Calculer les jours qui se trouvent entre la date d'arrivée et la date de départ
            $resultat = range(
                $booking->getDateArrivee()->getTimestamp(),
                $booking->getDateDepart()->getTimestamp(),
                24 * 60 * 60
            );

            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);

            $notAvailableDays = array_merge($notAvailableDays, $days);
        } 

        return $notAvailableDays;

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

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Reservation $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAnnonce($this);
        }

        return $this;
    }

    public function removeBooking(Reservation $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAnnonce() === $this) {
                $booking->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }
}
