<?php

namespace App\Entity;

use App\Repository\TbJemaatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbJemaatRepository::class)
 */
class TbJemaat
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
    private $id_jemaat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $nik;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $nama;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $tmp_lahir;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $tgl_lahir;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('L', 'P')")
     */
    private $jen_kel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $alamat;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $pekerjaan;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $no_telpon;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0', '1')")
     */
    private $status;

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

    public function getIdJemaat(): ?int
    {
        return $this->id_jemaat;
    }

    public function setIdJemaat(?int $id_jemaat): self
    {
        $this->id_jemaat = $id_jemaat;

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

    public function getNik(): ?string
    {
        return $this->nik;
    }

    public function setNik(?string $nik): self
    {
        $this->nik = $nik;

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

    public function getTmpLahir(): ?string
    {
        return $this->tmp_lahir;
    }

    public function setTmpLahir(?string $tmp_lahir): self
    {
        $this->tmp_lahir = $tmp_lahir;

        return $this;
    }

    public function getTglLahir(): ?\DateTimeInterface
    {
        return $this->tgl_lahir;
    }

    public function setTglLahir(?\DateTimeInterface $tgl_lahir): self
    {
        $this->tgl_lahir = $tgl_lahir;

        return $this;
    }

    public function getJenKel(): ?string
    {
        return $this->jen_kel;
    }

    public function setJenKel(?string $jen_kel): self
    {
        $this->jen_kel = $jen_kel;

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

    public function getPekerjaan(): ?string
    {
        return $this->pekerjaan;
    }

    public function setPekerjaan(?string $pekerjaan): self
    {
        $this->pekerjaan = $pekerjaan;

        return $this;
    }

    public function getNoTelpon(): ?string
    {
        return $this->no_telpon;
    }

    public function setNoTelpon(?string $no_telpon): self
    {
        $this->no_telpon = $no_telpon;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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
