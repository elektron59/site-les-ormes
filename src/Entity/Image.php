<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="La légende de l'image doit faire au moins 10 caractères")
     */
    private $legende;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MobilHome", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mobilhome;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLegende(): ?string
    {
        return $this->legende;
    }

    public function setLegende(string $legende): self
    {
        $this->legende = $legende;

        return $this;
    }

    public function getMobilhome(): ?MobilHome
    {
        return $this->mobilhome;
    }

    public function setMobilhome(?MobilHome $mobilhome): self
    {
        $this->mobilhome = $mobilhome;

        return $this;
    }
}
