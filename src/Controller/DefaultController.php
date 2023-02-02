<?php

namespace App\Controller;

use App\Entity\TbGereja;
use App\Entity\TbInformasi;
use App\Entity\User;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }
    
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Home',
            'klasis'  => $this->mng->getRepository(User::class)->getDetail('1'),
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
            'galeri'  => $this->mng->getRepository(TbInformasi::class)->getGaleri()
        ];

        return $this->render('index.html.twig', $data);
    }

    /**
     * @Route("/kontak", name="kontak")
     */
    public function kontak()
    {
        $data = [
            'halaman' => 'Kontak',
            'klasis'  => $this->mng->getRepository(User::class)->getDetail('1'),
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
            'user'    => $this->mng->getRepository(User::class)->findOneBy(['id_users' => '1']),
        ];

        return $this->render('kontak.html.twig', $data);
    }

    /**
     * @Route("/tentang", name="tentang")
     */
    public function tentang()
    {
        $data = [
            'halaman' => 'Tentang Kami',
            'klasis'  => $this->mng->getRepository(User::class)->getDetail('1'),
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
        ];

        return $this->render('tentang.html.twig', $data);
    }
}
