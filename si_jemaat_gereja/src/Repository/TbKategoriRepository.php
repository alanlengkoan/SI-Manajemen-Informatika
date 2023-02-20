<?php

namespace App\Repository;

use App\Entity\TbKategori;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbKategori|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbKategori|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbKategori[]    findAll()
 * @method TbKategori[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbKategoriRepository extends ServiceEntityRepository
{
    private $mng;
    
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbKategori::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getAll()
    {
        $sql = "SELECT k.id_kategori, k.nama FROM App\Entity\TbKategori k ORDER BY k.upd DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
