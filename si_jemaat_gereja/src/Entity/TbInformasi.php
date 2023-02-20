<?php

namespace App\Entity;

use App\Repository\TbInformasiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TbInformasiRepository::class)
 */
class TbInformasi
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
    private $id_informasi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_gereja;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_kategori;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $judul;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $isi;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $gambar;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tanggal_publish;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('1', '0')")
     */
    private $status;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('1', '0')")
     */
    private $status_galeri;

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

    public function getIdInformasi(): ?int
    {
        return $this->id_informasi;
    }

    public function setIdInformasi(?int $id_informasi): self
    {
        $this->id_informasi = $id_informasi;

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

    public function getIdKategori(): ?int
    {
        return $this->id_kategori;
    }

    public function setIdKategori(?int $id_kategori): self
    {
        $this->id_kategori = $id_kategori;

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

    public function getTanggalPublish(): ?\DateTimeInterface
    {
        return $this->tanggal_publish;
    }

    public function setTanggalPublish(?\DateTimeInterface $tanggal_publish): self
    {
        $this->tanggal_publish = $tanggal_publish;

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

    public function getStatusGaleri(): ?string
    {
        return $this->status_galeri;
    }

    public function setStatusGaleri(?string $status_galeri): self
    {
        $this->status_galeri = $status_galeri;

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
