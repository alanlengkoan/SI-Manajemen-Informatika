<?php

namespace App\Repository;

use App\Entity\TbJadwalMinggu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbJadwalMinggu|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbJadwalMinggu|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbJadwalMinggu[]    findAll()
 * @method TbJadwalMinggu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbJadwalMingguRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbJadwalMinggu::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getDetail($id)
    {
        $sql = "SELECT jr.id_jadwal_minggu, jr.id_gereja, jr.nama_pelayan, jr.tanggal_ibadah FROM App\Entity\TbJadwalMinggu jr WHERE jr.id_gereja = '$id'";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil semua data
    public function getDetailJadwal($id)
    {
        $sql = "SELECT jr.id_jadwal_minggu, jr.id_gereja, jr.nama_pelayan, jr.tanggal_ibadah FROM App\Entity\TbJadwalMinggu jr WHERE jr.id_gereja = '$id' ORDER BY jr.tanggal_ibadah DESC";
        $qry = $this->mng->createQuery($sql)->setMaxResults(3)->getResult();
        return $qry;
    }

    // untuk mengambil jadwal ibadah minggu
    public function getDetailDate($id, $tgl_ibadah_awal, $tgl_ibadah_akhir)
    {
        $sql = "SELECT jr.id_jadwal_minggu, jr.id_gereja, jr.nama_pelayan, jr.tanggal_ibadah FROM App\Entity\TbJadwalMinggu jr WHERE jr.id_gereja = '$id' AND (DATE(jr.tanggal_ibadah) BETWEEN '$tgl_ibadah_awal' AND '$tgl_ibadah_akhir')";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
