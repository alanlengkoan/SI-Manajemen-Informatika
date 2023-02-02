<?php

namespace App\Repository;

use App\Entity\TbProfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbProfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbProfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbProfil[]    findAll()
 * @method TbProfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbProfilRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbProfil::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getRincian($id)
    {
        $sql = "SELECT p.id_profil, p.judul, p.isi, p.gambar FROM App\Entity\TbProfil AS p ORDER BY p.ins DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }
}
