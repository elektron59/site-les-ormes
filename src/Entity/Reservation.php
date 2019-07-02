<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MobilHome", inversedBy="yes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date d'arrivée doit être au bon format !")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui !")
     */
    private $dateArrivee;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date de départ doit être au bon format !")
     * @Assert\GreaterThan(propertyPath="dateArrivee", message="La date de départ doit être plus éloignée que la date d'arrivée !")
     */
    private $dateDepart; 

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * Callback appelé à chaque fois qu'on crée une réservation
     * 
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist() {
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }

        if(empty($this->montant)){
            // prix de l'annonce * nombre de jour
            $this->montant =  $this->annonce->getTarifJourHsMh() * $this->getDuration();
        }
    }

    public function isBookableDate() {
        // 1) Il faut connaître les dates qui sont impossibles pour l'annonce
        $notAvailableDays = $this->annonce->getNotAvailableDays();
        // 2) Il faut comparer les dates choisies avec les dates impossibles
        $bookingDays = $this->getDays();

        $formatDays = function($day){
            return $day->format('Y-m-d');
        };

        // Tableau des chaines de caractères de mes journées
        $days           = array_map($formatDays, $bookingDays);
        $notAvailable   = array_map($formatDays, $notAvailableDays);

        foreach($days as $day) {
            if (array_search($day, $notAvailable) !== false) return false;
        }

        return true;
    }

    /**
     * Permet de récupérer un tableau des journées qui correspondent à ma réservation
     *
     * @return array Un tableau d'objets DateTime représentant les jours de la réservation
     */
    public function getDays() {
        $resultat = range(
            $this->dateArrivee->getTimestamp(),
            $this->dateDepart->getTimestamp(),
            24 * 60 * 60
        );
        
        $days = array_map(function($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        },$resultat);

        return $days;
    }

    // calcul le nombre de jours entre la date de départ et la date d'arrivée
    public function getDuration(){
        $diff = $this->dateDepart->diff($this->dateArrivee);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAnnonce(): ?MobilHome
    {
        return $this->annonce;
    }

    public function setAnnonce(?MobilHome $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->ceatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $ceatedAt): self
    {
        $this->ceatedAt = $ceatedAt;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
