<?php

namespace App\Repository;

use App\Entity\TbJemaat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbJemaat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbJemaat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbJemaat[]    findAll()
 * @method TbJemaat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbJemaatRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbJemaat::class);
        $this->mng = $mng;
    }

    // untuk mengambil ulang tahun
    public function getDetailDate($id, $tgl_awal, $tgl_akhir)
    {
        $sql = "SELECT j.id_jemaat, j.nik, j.nama, j.tmp_lahir, j.tgl_lahir, j.jen_kel, j.alamat, j.pekerjaan, j.no_telpon FROM App\Entity\TbJemaat j WHERE j.id_gereja = '$id' AND DATE_FORMAT(j.tgl_lahir, '%m-%d') BETWEEN DATE_FORMAT('$tgl_awal', '%m-%d') AND DATE_FORMAT('$tgl_akhir', '%m-%d')";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengecek nik apa bila sudah terdaftar
    public function checkNik($nik)
    {
        $sql = "SELECT j.id_jemaat, j.nik, j.nama, j.tmp_lahir, j.tgl_lahir, j.jen_kel, j.alamat, j.pekerjaan, j.no_telpon FROM App\Entity\TbJemaat j WHERE j.nik LIKE '%$nik%' ";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
