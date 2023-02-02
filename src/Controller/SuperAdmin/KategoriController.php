<?php

namespace App\Controller\SuperAdmin;

use App\Entity\TbJadwal;
use App\Entity\TbKategori;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class KategoriController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/superadmin/kategori", name="superadmin_kategori")
     */
    // fungsi default
    public function index()
    {
        $data = [
            'halaman'  => 'Kategori Informasi',
            'kategori' => $this->mng->getRepository(TbKategori::class)->getAll(),
        ];

        return $this->render('superadmin/kategori_informasi/view.html.twig', $data);
    }

    /**
     * @Route("/superadmin/kategori/add", name="superadmin_kategori_add")
     */
    // fungsi untuk tambah data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek apa bila token sesuai
            if ($this->isCsrfTokenValid('add', $token)) {
                try {
                    $kategori = new TbKategori();
                    $kategori->setIdKategori($this->myfun->getIdOtomatis('tb_kategori'));
                    $kategori->setNama($post->request->get('inpnama'));
                    $kategori->setIns(date_create());
                    $kategori->setUpd(date_create());

                    $this->mng->persist($kategori);
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
     * @Route("/superadmin/kategori/get", name="superadmin_kategori_get")
     */
    // fungsi untuk get data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $kategori = $this->mng->getRepository(TbKategori::class)->findOneBy(['id_kategori' => $id]);

        $data = [
            'id_kategori' => $kategori->getIdKategori(),
            'nama'        => $kategori->getNama(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/superadmin/kategori/upd", name="superadmin_kategori_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        try {
            $id = $post->request->get('inpidkategori');
            $kategori = $this->mng->getRepository(TbKategori::class)->findOneBy(['id_kategori' => $id]);

            $kategori->setNama($post->request->get('inpnama'));
            $kategori->setUpd(date_create());

            $this->mng->persist($kategori);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/superadmin/kategori/del", name="superadmin_kategori_del")
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $jadwal = $this->mng->getRepository(TbKategori::class)->findOneBy(['id_kategori' => $id]);

            $this->mng->remove($jadwal);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
