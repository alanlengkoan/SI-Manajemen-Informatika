<?php

namespace App\Entity;

use App\Repository\TbJadwalMingguRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbJadwalMingguRepository::class)
 */
class TbJadwalMinggu
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
    private $id_jadwal_minggu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nama_pelayan;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tanggal_ibadah;

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

    public function getIdJadwalMinggu(): ?int
    {
        return $this->id_jadwal_minggu;
    }

    public function setIdJadwalMinggu(?int $id_jadwal_minggu): self
    {
        $this->id_jadwal_minggu = $id_jadwal_minggu;

        return $this;
    }

    public function getNamaPelayan(): ?string
    {
        return $this->nama_pelayan;
    }

    public function setNamaPelayan(?string $nama_pelayan): self
    {
        $this->nama_pelayan = $nama_pelayan;

        return $this;
    }

    public function getTanggalIbadah(): ?\DateTimeInterface
    {
        return $this->tanggal_ibadah;
    }

    public function setTanggalIbadah(?\DateTimeInterface $tanggal_ibadah): self
    {
        $this->tanggal_ibadah = $tanggal_ibadah;

        return $this;
    }
}
