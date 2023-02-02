<?php

namespace App\Repository;

use App\Entity\TbPengurus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbPengurus|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbPengurus|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbPengurus[]    findAll()
 * @method TbPengurus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbPengurusRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbPengurus::class);
        $this->mng = $mng;
    }
    
    // untuk mengambil semua data
    public function getDetail($id)
    {
        $sql = "SELECT p.id_pengurus, p.nama, p.jabatan, p.foto FROM App\Entity\TbPengurus p WHERE p.id_gereja = '$id' ORDER BY p.upd DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}