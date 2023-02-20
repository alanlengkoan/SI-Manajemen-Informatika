<?php

namespace App\Controller\SuperAdmin;

use App\Entity\TbInformasi;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/superadmin/galeri", name="superadmin_galeri")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Galeri',
            'data'    => $this->mng->getRepository(TbInformasi::class)->getAll(),
        ];

        return $this->render('superadmin/galeri/view.html.twig', $data);
    }

    /**
     * @Route("/superadmin/galeri/upd_status", name="superadmin_informasi_upd_status")
     */
    // untuk ubah status data
    public function upd_status(Request $post)
    {
        // untuk mengecek apa bila terdapat request
        if ($post->request->count() > 0) {
            try {
                $kd        = $post->request->get('id');
                $status    = $post->request->get('status');
                $informasi = $this->mng->getRepository(TbInformasi::class)->findOneBy(['id_informasi' => $kd]);

                if ($status == '1') {
                    $informasi->setStatusGaleri('0');
                    $informasi->setUpd(date_create());
                } else {
                    $informasi->setStatusGaleri('1');
                    $informasi->setUpd(date_create());
                }

                $this->mng->persist($informasi);
                $this->mng->flush();

                $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah status', 'type' => 'success', 'button' => 'Ok!'];
            } catch (Exception $th) {
                $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah status', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
