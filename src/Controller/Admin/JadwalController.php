<?php

namespace App\Controller\Admin;

use App\Entity\TbJadwal;
use App\Entity\TbJadwalRincian;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/admin/jadwal", name="admin_jadwal")
     */
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman' => 'Informasi Jadwal',
            'jadwal'  => $this->mng->getRepository(TbJadwal::class)->getAll(),
            'data'    => $this->mng->getRepository(TbJadwalRincian::class)->getDetail($idu),
        ];

        return $this->render('admin/jadwal/view.html.twig', $data);
    }

    /**
     * @Route("/admin/jadwal/add", name="admin_jadwal_add")
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

                    $jadwalDetail = new TbJadwalRincian();
                    $jadwalDetail->setIdJadwalRincian($this->myfun->getIdOtomatis('tb_jadwal_rincian'));
                    $jadwalDetail->setIdGereja($idu);
                    $jadwalDetail->setIdJadwal($post->request->get('inpidjadwal'));
                    $jadwalDetail->setNamaKeluarga($post->request->get('inpnamakel'));
                    $jadwalDetail->setNamaPelayan($post->request->get('inpnamapel'));
                    $jadwalDetail->setAlamat($post->request->get('inpalamat'));
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
     * @Route("/admin/jadwal/get", name="admin_jadwal_get")
     */
    // untuk ambil data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $jadwalRincian = $this->mng->getRepository(TbJadwalRincian::class)->findOneBy(['id_jadwal_rincian' => $id]);

        $data = [
            'id'                => $jadwalRincian->getId(),
            'id_jadwal_rincian' => $jadwalRincian->getIdJadwalRincian(),
            'id_jadwal'         => $jadwalRincian->getIdJadwal(),
            'id_gereja'         => $jadwalRincian->getIdGereja(),
            'nama_keluarga'     => $jadwalRincian->getNamaKeluarga(),
            'nama_pelayan'      => $jadwalRincian->getNamaPelayan(),
            'alamat'            => $jadwalRincian->getAlamat(),
            'tanggal_ibadah'    => $jadwalRincian->getTanggalIbadah()->format('Y-m-d\TH:i'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/jadwal/upd", name="admin_jadwal_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        try {
            $id = $post->request->get('inpidjadwalrincian');
            $jadwalRimciam = $this->mng->getRepository(TbJadwalRincian::class)->findOneBy(['id_jadwal_rincian' => $id]);

            $tanggalIbadah = date('Y-m-d H:i', strtotime($post->request->get('inptglibadah')));

            $jadwalRimciam->setIdJadwal($post->request->get('inpidjadwal'));
            $jadwalRimciam->setNamaKeluarga($post->request->get('inpnamakel'));
            $jadwalRimciam->setNamaPelayan($post->request->get('inpnamapel'));
            $jadwalRimciam->setAlamat($post->request->get('inpalamat'));
            $jadwalRimciam->setTanggalIbadah(\DateTime::createFromFormat('Y-m-d H:i', $tanggalIbadah));

            $this->mng->persist($jadwalRimciam);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/jadwal/del", name="admin_jadwal_del")
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $jadwal = $this->mng->getRepository(TbJadwalRincian::class)->findOneBy(['id_jadwal_rincian' => $id]);

            $this->mng->remove($jadwal);
            $this->mng->flush();

            $response = ['title' => 'Barhasil!', 'text' => 'Berhasil dihapus', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
