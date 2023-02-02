<?php

namespace App\Controller;

use App\Entity\TbGereja;
use App\Entity\TbInformasi;
use App\Entity\User;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GaleriController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }
    
    /**
     * @Route("/galeri", name="galeri")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Galeri',
            'klasis'  => $this->mng->getRepository(User::class)->getDetail('1'),
            'data'    => $this->mng->getRepository(TbInformasi::class)->getGaleri(),
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
        ];

        return $this->render('galeri.html.twig', $data);
    }
}
