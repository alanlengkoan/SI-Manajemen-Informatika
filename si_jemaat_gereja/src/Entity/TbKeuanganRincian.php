<?php

namespace App\Entity;

use App\Repository\TbKeuanganRincianRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbKeuanganRincianRepository::class)
 */
class TbKeuanganRincian
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
    private $id_keuangan_rincian;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_keuangan;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $keterangan;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $gambar;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $tanggal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $debit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kredit;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('d', 'k')")
     */
    private $status_u;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdKeuanganRincian(): ?int
    {
        return $this->id_keuangan_rincian;
    }

    public function setIdKeuanganRincian(?int $id_keuangan_rincian): self
    {
        $this->id_keuangan_rincian = $id_keuangan_rincian;

        return $this;
    }

    public function getIdKeuangan(): ?int
    {
        return $this->id_keuangan;
    }

    public function setIdKeuangan(?int $id_keuangan): self
    {
        $this->id_keuangan = $id_keuangan;

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

    public function getKeterangan(): ?string
    {
        return $this->keterangan;
    }

    public function setKeterangan(?string $keterangan): self
    {
        $this->keterangan = $keterangan;

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

    public function getTanggal(): ?\DateTimeInterface
    {
        return $this->tanggal;
    }

    public function setTanggal(?\DateTimeInterface $tanggal): self
    {
        $this->tanggal = $tanggal;

        return $this;
    }

    public function getDebit(): ?int
    {
        return $this->debit;
    }

    public function setDebit(?int $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getKredit(): ?int
    {
        return $this->kredit;
    }

    public function setKredit(?int $kredit): self
    {
        $this->kredit = $kredit;

        return $this;
    }

    public function getStatusU(): ?string
    {
        return $this->status_u;
    }

    public function setStatusU(?string $status_u): self
    {
        $this->status_u = $status_u;

        return $this;
    }
}
