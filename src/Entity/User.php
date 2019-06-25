<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Un autre utilisateur s'est déjà inscrit avec cette adresse email, merci de la modifier"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */ 
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prénom")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Vous n'aves pas correctement confirmé votre mot de passe !")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=30, minMessage="Votre présentation doit faire au moins 30 caractères")
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Veuillez donner une URL valide pour votre avatar !")
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MobilHome", mappedBy="auteur")
     */
    private $mobilHomes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * Concatene le prénom et le nom de l'utilisateur
     */
    public function getFullName() {
        return "{$this->prenom} {$this->nom}";
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
        if(empty($this->slug)) { // Si slug est vide
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->prenom .' '.$this->nom); // transforme en slug prenom + nom
        }
    }


    public function __construct()
    {
        $this->mobilHomes = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|MobilHome[]
     */
    public function getMobilHomes(): Collection
    {
        return $this->mobilHomes;
    }

    public function addMobilHome(MobilHome $mobilHome): self
    {
        if (!$this->mobilHomes->contains($mobilHome)) {
            $this->mobilHomes[] = $mobilHome;
            $mobilHome->setAuteur($this);
        }

        return $this;
    }

    public function removeMobilHome(MobilHome $mobilHome): self
    {
        if ($this->mobilHomes->contains($mobilHome)) {
            $this->mobilHomes->removeElement($mobilHome);
            // set the owning side to null (unless already changed)
            if ($mobilHome->getAuteur() === $this) {
                $mobilHome->setAuteur(null);
            }
        }

        return $this;
    }

    public function getRoles() {
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword() {
        return $this->hash;
    }

    public function getSalt(){}

    public function getUsername() {
        return $this->email;
    }

    public function eraseCredentials() {}

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRoles): self
    {
        if (!$this->userRoles->contains($userRoles)) {
            $this->userRoles[] = $userRoles;
            $userRoles->addUser($this);
        }

        return $this;
    }

    public function removeUserRoles(Role $userRoles): self
    {
        if ($this->userRoles->contains($userRoles)) {
            $this->userRoles->removeElement($userRoles);
            $userRoles->removeUser($this);
        }

        return $this;
    }
}
