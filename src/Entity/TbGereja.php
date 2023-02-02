<?php

namespace App\Entity;

use App\Repository\TbGerejaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbGerejaRepository::class)
 */
class TbGereja
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tentang;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $youtube;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lon;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ins;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $upd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdGereja(): ?int
    {
        return $this->id_gereja;
    }

    public function setIdGereja(?int $id_gereja): self
    {
        $this->id_gereja = $id_gereja;

        return $this;
    }

    public function getTentang(): ?string
    {
        return $this->tentang;
    }

    public function setTentang(?string $tentang): self
    {
        $this->tentang = $tentang;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(?float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getIns(): ?\DateTimeInterface
    {
        return $this->ins;
    }

    public function setIns(?\DateTimeInterface $ins): self
    {
        $this->ins = $ins;

        return $this;
    }

    public function getUpd(): ?\DateTimeInterface
    {
        return $this->upd;
    }

    public function setUpd(?\DateTimeInterface $upd): self
    {
        $this->upd = $upd;

        return $this;
    }
}
