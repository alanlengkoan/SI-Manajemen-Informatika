<?php

namespace App\Repository;

use App\Entity\TbKeuangan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbKeuangan|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbKeuangan|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbKeuangan[]    findAll()
 * @method TbKeuangan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbKeuanganRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbKeuangan::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getAll()
    {
        $sql = "SELECT k.id_keuangan, k.nama FROM App\Entity\TbKeuangan k ORDER BY k.upd DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
