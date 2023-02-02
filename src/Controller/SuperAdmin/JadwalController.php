<?php

namespace App\Controller\SuperAdmin;

use App\Entity\TbJadwal;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class JadwalController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/superadmin/jadwal", name="superadmin_jadwal")
     */
    // fungsi default
    public function index()
    {
        $data = [
            'halaman' => 'Kategori Jadwal',
            'jadwal'  => $this->mng->getRepository(TbJadwal::class)->getAll(),
        ];

        return $this->render('superadmin/kategori_jadwal/view.html.twig', $data);
    }

    /**
     * @Route("/superadmin/jadwal/add", name="superadmin_jadwal_add")
     */
    // fungsi untuk tambah data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek apa bila token sesuai
            if ($this->isCsrfTokenValid('add', $token)) {
                try {
                    $jadwal = new TbJadwal();
                    $jadwal->setIdJadwal($this->myfun->getIdOtomatis('tb_jadwal'));
                    $jadwal->setNama($post->request->get('inpnama'));
                    $jadwal->setIns(date_create());
                    $jadwal->setUpd(date_create());

                    $this->mng->persist($jadwal);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data!', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $e) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal tambah data!', 'type' => 'error', 'button' => 'Ok!'];
                }
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/superadmin/jadwal/get", name="superadmin_jadwal_get")
     */
    // fungsi untuk get data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $jadwal = $this->mng->getRepository(TbJadwal::class)->findOneBy(['id_jadwal' => $id]);

        $data = [
            'id_jadwal' => $jadwal->getIdJadwal(),
            'nama'      => $jadwal->getNama(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/superadmin/jadwal/upd", name="superadmin_jadwal_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        try {
            $id = $post->request->get('inpidjadwal');
            $jadwal = $this->mng->getRepository(TbJadwal::class)->findOneBy(['id_jadwal' => $id]);

            $jadwal->setNama($post->request->get('inpnama'));
            $jadwal->setUpd(date_create());

            $this->mng->persist($jadwal);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/superadmin/jadwal/del", name="superadmin_jadwal_del")
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $jadwal = $this->mng->getRepository(TbJadwal::class)->findOneBy(['id_jadwal' => $id]);

            $this->mng->remove($jadwal);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
