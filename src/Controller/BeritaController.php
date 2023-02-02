<?php

namespace App\Controller;

use App\Entity\TbGereja;
use App\Entity\TbInformasi;
use App\Entity\User;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BeritaController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }
    
    /**
     * @Route("/berita", name="berita")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Berita',
            'klasis'  => $this->mng->getRepository(User::class)->getDetail('1'),
            'berita'  => $this->mng->getRepository(TbInformasi::class)->getAll(),
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
        ];

        return $this->render('berita.html.twig', $data);
    }

    /**
     * @Route("/berita/{id}", name="berita_detail")
     */
    public function detail(int $id)
    {
        $data = [
            'halaman' => 'Detail Berita',
            'berita'  => $this->mng->getRepository(TbInformasi::class)->getDetail($id)
        ];

        return $this->render('berita_det.html.twig', $data);
    }
}
