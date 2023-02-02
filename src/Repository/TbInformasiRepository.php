<?php

namespace App\Repository;

use App\Entity\TbInformasi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbInformasi|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbInformasi|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbInformasi[]    findAll()
 * @method TbInformasi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbInformasiRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbInformasi::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getAll()
    {
        $sql = "SELECT i.id_informasi, i.judul, i.isi, i.gambar, i.tanggal_publish, i.status, i.status_galeri, k.nama AS nama_kategori, u.nama AS gereja FROM App\Entity\TbInformasi i LEFT JOIN App\Entity\TbKategori k WITH i.id_kategori= k.id_kategori LEFT JOIN App\Entity\User u WITH i.id_gereja = u.id_users WHERE i.status = '1' ORDER BY i.tanggal_publish DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil semua data
    public function getDetail($id)
    {
        $sql = "SELECT i.id_informasi, i.judul, i.isi, i.gambar, i.tanggal_publish, i.status, i.status_galeri, k.nama AS nama_kategori, u.nama AS gereja FROM App\Entity\TbInformasi i LEFT JOIN App\Entity\TbKategori k WITH i.id_kategori= k.id_kategori LEFT JOIN App\Entity\User u WITH i.id_gereja = u.id_users WHERE i.status = '1' AND i.id_informasi = '$id'";
        $qry = $this->mng->createQuery($sql)->getOneOrNullResult();
        return $qry;
    }

    // untuk mengambil semua data
    public function getGaleri()
    {
        $sql = "SELECT i.id_informasi, i.judul, i.isi, i.gambar, i.tanggal_publish, i.status, i.status_galeri, k.nama AS nama_kategori FROM App\Entity\TbInformasi i LEFT JOIN App\Entity\TbKategori k WITH i.id_kategori= k.id_kategori WHERE i.status = '1' AND i.status_galeri = '1' ORDER BY i.tanggal_publish DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil semua data
    public function getRincian($id)
    {
        $sql = "SELECT i.id_informasi, i.judul, i.isi, i.gambar, i.tanggal_publish, i.status, k.nama AS nama_kategori FROM App\Entity\TbInformasi i LEFT JOIN App\Entity\TbKategori k WITH i.id_kategori= k.id_kategori WHERE i.id_gereja = '$id' ORDER BY i.tanggal_publish DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
