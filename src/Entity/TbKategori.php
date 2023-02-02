<?php

namespace App\Entity;

use App\Repository\TbKategoriRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbKategoriRepository::class)
 */
class TbKategori
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
    private $id_kategori;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $nama;

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

    public function getIdKategori(): ?int
    {
        return $this->id_kategori;
    }

    public function setIdKategori(?int $id_kategori): self
    {
        $this->id_kategori = $id_kategori;

        return $this;
    }

    public function getNama(): ?string
    {
        return $this->nama;
    }

    public function setNama(?string $nama): self
    {
        $this->nama = $nama;

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
