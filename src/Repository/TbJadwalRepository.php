<?php

namespace App\Repository;

use App\Entity\TbJadwal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbJadwal|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbJadwal|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbJadwal[]    findAll()
 * @method TbJadwal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbJadwalRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbJadwal::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getAll()
    {
        $sql = "SELECT j.id_jadwal, j.nama FROM App\Entity\TbJadwal j ORDER BY j.upd DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
