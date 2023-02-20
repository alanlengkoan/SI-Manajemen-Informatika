<?php

namespace App\Repository;

use App\Entity\TbGereja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbGereja|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbGereja|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbGereja[]    findAll()
 * @method TbGereja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbGerejaRepository extends ServiceEntityRepository
{
    private $mng;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $mng)
    {
        parent::__construct($registry, TbGereja::class);
        $this->mng = $mng;
    }

    // untuk mengambil semua data
    public function getAll()
    {
        $sql = "SELECT g.id_gereja, g.tentang, g.twitter, g.instagram, g.facebook, g.youtube, g.lat, g.lon, u.nama, u.alamat, u.telepon, u.email, u.foto, u.username FROM App\Entity\TbGereja g LEFT JOIN App\Entity\User u WITH g.id_gereja = u.id_users ORDER BY g.upd DESC";
        $qry = $this->mng->createQuery($sql)->getResult();
        return $qry;
    }

    // untuk mengambil detail gereja dan user
    public function getDetail($id)
    {
        $sql = "SELECT g.id_gereja, g.tentang, g.twitter, g.instagram, g.facebook, g.youtube, g.lat, g.lon, u.nama, u.alamat, u.telepon, u.jadwal_ibadah_operasional AS jadwal_ibadah, u.email, u.foto, u.username FROM App\Entity\TbGereja g LEFT JOIN App\Entity\User u WITH g.id_gereja = u.id_users WHERE u.id_users='$id'";
        $qry = $this->mng->createQuery($sql)->getOneOrNullResult();
        return $qry;
    }
}
