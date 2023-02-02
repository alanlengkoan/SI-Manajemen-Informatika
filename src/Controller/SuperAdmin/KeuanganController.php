<?php

namespace App\Controller\SuperAdmin;

use App\Entity\TbKeuangan;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class KeuanganController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/superadmin/keuangan", name="superadmin_keuangan")
     */
    // fungsi default
    public function index()
    {
        $data = [
            'halaman'  => 'Kategori Keuangan',
            'keuangan' => $this->mng->getRepository(TbKeuangan::class)->getAll(),
        ];

        return $this->render('superadmin/kategori_keuangan/view.html.twig', $data);
    }

    /**
     * @Route("/superadmin/keuangan/add", name="superadmin_keuangan_add")
     */
    // fungsi untuk tambah data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek apa bila token sesuai
            if ($this->isCsrfTokenValid('add', $token)) {
                try {
                    $keuangan = new TbKeuangan();
                    $keuangan->setIdKeuangan($this->myfun->getIdOtomatis('tb_keuangan'));
                    $keuangan->setNama($post->request->get('inpnama'));
                    $keuangan->setIns(date_create());
                    $keuangan->setUpd(date_create());

                    $this->mng->persist($keuangan);
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
     * @Route("/superadmin/keuangan/get", name="superadmin_keuangan_get")
     */
    // fungsi untuk get data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $keuangan = $this->mng->getRepository(TbKeuangan::class)->findOneBy(['id_keuangan' => $id]);

        $data = [
            'id_keuangan' => $keuangan->getIdKeuangan(),
            'nama'        => $keuangan->getNama(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/superadmin/keuangan/upd", name="superadmin_keuangan_upd")
     */
    // fungsi untuk ubah data
    public function upd(Request $post)
    {
        try {
            $id = $post->request->get('inpidkeuangan');
            $keuangan = $this->mng->getRepository(TbKeuangan::class)->findOneBy(['id_keuangan' => $id]);

            $keuangan->setNama($post->request->get('inpnama'));
            $keuangan->setUpd(date_create());

            $this->mng->persist($keuangan);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/superadmin/keuangan/del", name="superadmin_keuangan_del")
     */
    // fungsi untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $keuangan = $this->mng->getRepository(TbKeuangan::class)->findOneBy(['id_keuangan' => $id]);

            $this->mng->remove($keuangan);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
