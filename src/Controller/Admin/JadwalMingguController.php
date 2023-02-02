<?php

namespace App\Controller\Admin;

use App\Entity\TbJadwalMinggu;
use App\Entity\TbJadwalRincian;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JadwalMingguController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/admin/jadwal_minggu", name="admin_jadwal_minggu")
     */
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman' => 'Informasi Jadwal Ibadah Minggu',
            'data'    => $this->mng->getRepository(TbJadwalMinggu::class)->getDetail($idu),
        ];

        return $this->render('admin/jadwal_minggu/view.html.twig', $data);
    }

    /**
     * @Route("/admin/jadwal_minggu/add", name="admin_jadwal_minggu_add")
     */
    // untuk tambah data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                try {
                    $idu = $this->getUser()->id_users;
                    $tanggalIbadah = date('Y-m-d H:i', strtotime($post->request->get('inptglibadah')));

                    $jadwalDetail = new TbJadwalMinggu();
                    $jadwalDetail->setIdGereja($idu);
                    $jadwalDetail->setIdJadwalMinggu($this->myfun->getIdOtomatis('tb_jadwal_minggu'));
                    $jadwalDetail->setNamaPelayan($post->request->get('inpnamapel'));
                    $jadwalDetail->setTanggalIbadah(\DateTime::createFromFormat('Y-m-d H:i', $tanggalIbadah));


                    $this->mng->persist($jadwalDetail);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $th) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal tambah data', 'type' => 'error', 'button' => 'Ok!'];
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
     * @Route("/admin/jadwal_minggu/get", name="admin_jadwal_minggu_get")
     */
    // untuk ambil data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $jadwalMinggu = $this->mng->getRepository(TbJadwalMinggu::class)->findOneBy(['id_jadwal_minggu' => $id]);

        $data = [
            'id'               => $jadwalMinggu->getId(),
            'id_jadwal_minggu' => $jadwalMinggu->getIdJadwalMinggu(),
            'id_gereja'        => $jadwalMinggu->getIdGereja(),
            'nama_pelayan'     => $jadwalMinggu->getNamaPelayan(),
            'tanggal_ibadah'   => $jadwalMinggu->getTanggalIbadah()->format('Y-m-d\TH:i'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/jadwal_minggu/upd", name="admin_jadwal_minggu_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        try {
            $id = $post->request->get('inpidjadwalminggu');
            $jadwalMinggu = $this->mng->getRepository(TbJadwalMinggu::class)->findOneBy(['id_jadwal_minggu' => $id]);

            $tanggalIbadah = date('Y-m-d H:i', strtotime($post->request->get('inptglibadah')));

            $jadwalMinggu->setNamaPelayan($post->request->get('inpnamapel'));
            $jadwalMinggu->setTanggalIbadah(\DateTime::createFromFormat('Y-m-d H:i', $tanggalIbadah));

            $this->mng->persist($jadwalMinggu);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/jadwal_minggu/del", name="admin_jadwal_minggu_del")
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $jadwalMinggu = $this->mng->getRepository(TbJadwalMinggu::class)->findOneBy(['id_jadwal_minggu' => $id]);

            $this->mng->remove($jadwalMinggu);
            $this->mng->flush();

            $response = ['title' => 'Barhasil!', 'text' => 'Berhasil dihapus', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
