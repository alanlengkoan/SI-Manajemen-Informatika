<?php

namespace App\Entity;

use App\Repository\TbProfilRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbProfilRepository::class)
 */
class TbProfil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=true)
     */
    private $id_profil;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $judul;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $isi;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $gambar;

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

    public function getIdProfil(): ?int
    {
        return $this->id_profil;
    }

    public function setIdProfil(?int $id_profil): self
    {
        $this->id_profil = $id_profil;

        return $this;
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

    public function getJudul(): ?string
    {
        return $this->judul;
    }

    public function setJudul(?string $judul): self
    {
        $this->judul = $judul;

        return $this;
    }

    public function getIsi(): ?string
    {
        return $this->isi;
    }

    public function setIsi(?string $isi): self
    {
        $this->isi = $isi;

        return $this;
    }

    public function getGambar(): ?string
    {
        return $this->gambar;
    }

    public function setGambar(?string $gambar): self
    {
        $this->gambar = $gambar;

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
