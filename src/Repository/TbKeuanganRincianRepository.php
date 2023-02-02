<?php

namespace App\Repository;

use App\Entity\TbKeuanganRincian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbKeuanganRincian|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbKeuanganRincian|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbKeuanganRincian[]    findAll()
 * @method TbKeuanganRincian[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbKeuanganRincianRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbKeuanganRincian::class);
        $this->mng = $mng;
    }

    // untuk mengambil laporan 
    public function getAll($id, $tglawal, $tglakhir)
    {
        $sql = "SELECT kr.id_keuangan_rincian, kr.keterangan, k.nama AS nama_keuangan, COALESCE(kr.debit, 0) AS debit, COALESCE(kr.kredit, 0) AS kredit, kr.tanggal, k.ins FROM App\Entity\TbKeuanganRincian kr LEFT JOIN App\Entity\TbKeuangan k WITH kr.id_keuangan = k.id_keuangan WHERE kr.id_gereja = '$id' AND kr.tanggal BETWEEN '$tglawal' AND '$tglakhir' ORDER BY kr.tanggal ASC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil data pemasukan
    public function getPemasukan($id)
    {
        $sql = "SELECT kr.id_keuangan_rincian, kr.debit, kr.keterangan, kr.gambar, kr.tanggal, k.nama AS nama_keuangan FROM App\Entity\TbKeuanganRincian kr LEFT JOIN App\Entity\TbKeuangan k WITH kr.id_keuangan= k.id_keuangan WHERE kr.id_gereja = '$id' AND kr.status_u = 'd'";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil data pengeluaran
    public function getPengeluaran($id)
    {
        $sql = "SELECT kr.id_keuangan_rincian, kr.kredit, kr.keterangan, kr.gambar, kr.tanggal, k.nama AS nama_keuangan FROM App\Entity\TbKeuanganRincian kr LEFT JOIN App\Entity\TbKeuangan k WITH kr.id_keuangan= k.id_keuangan WHERE kr.id_gereja = '$id' AND kr.status_u = 'k'";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil data pemasukan
    public function getDetailPemasukan($id, $tglawal, $tglakhir)
    {
        $sql = "SELECT kr.id_keuangan_rincian, kr.debit, kr.keterangan, kr.gambar, kr.tanggal, k.nama AS nama_keuangan FROM App\Entity\TbKeuanganRincian kr LEFT JOIN App\Entity\TbKeuangan k WITH kr.id_keuangan= k.id_keuangan WHERE kr.id_gereja = '$id' AND kr.status_u = 'd' AND (kr.tanggal BETWEEN '$tglawal' AND '$tglakhir')";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil data pengeluaran
    public function getDetailPengeluaran($id, $tglawal, $tglakhir)
    {
        $sql = "SELECT kr.id_keuangan_rincian, kr.kredit, kr.keterangan, kr.gambar, kr.tanggal, k.nama AS nama_keuangan FROM App\Entity\TbKeuanganRincian kr LEFT JOIN App\Entity\TbKeuangan k WITH kr.id_keuangan= k.id_keuangan WHERE kr.id_gereja = '$id' AND kr.status_u = 'k' AND (kr.tanggal BETWEEN '$tglawal' AND '$tglakhir')";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
