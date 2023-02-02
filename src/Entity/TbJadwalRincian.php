<?php

namespace App\Entity;

use App\Repository\TbJadwalRincianRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbJadwalRincianRepository::class)
 */
class TbJadwalRincian
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
    private $id_jadwal_rincian;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_jadwal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nama_keluarga;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nama_pelayan;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $alamat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tanggal_ibadah;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdJadwalRincian(): ?int
    {
        return $this->id_jadwal_rincian;
    }

    public function setIdJadwalRincian(?int $id_jadwal_rincian): self
    {
        $this->id_jadwal_rincian = $id_jadwal_rincian;

        return $this;
    }

    public function getIdJadwal(): ?int
    {
        return $this->id_jadwal;
    }

    public function setIdJadwal(?int $id_jadwal): self
    {
        $this->id_jadwal = $id_jadwal;

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

    public function getNamaKeluarga(): ?string
    {
        return $this->nama_keluarga;
    }

    public function setNamaKeluarga(?string $nama_keluarga): self
    {
        $this->nama_keluarga = $nama_keluarga;

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

    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    public function setAlamat(?string $alamat): self
    {
        $this->alamat = $alamat;

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
