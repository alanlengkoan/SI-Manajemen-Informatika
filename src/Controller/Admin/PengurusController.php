<?php

namespace App\Controller\Admin;

use App\Entity\TbGereja;
use App\Entity\TbPengurus;
use App\Entity\User;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PengurusController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/admin/pengurus", name="admin_pengurus")
     */
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman' => 'Pengurus',
            'data'    => $this->mng->getRepository(TbPengurus::class)->getDetail($idu),
        ];

        return $this->render('admin/pengurus/view.html.twig', $data);
    }

    /**
     * @Route("/admin/pengurus/get_data_pengurus", name="admin_get_data_pengurus", methods={"POST"})
     */
    // untuk ambil data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $pengurus = $this->mng->getRepository(TbPengurus::class)->findOneBy(['id_pengurus' => $id]);

        $data = [
            'id'          => $pengurus->getId(),
            'id_pengurus' => $pengurus->getIdPengurus(),
            'id_gereja'   => $pengurus->getIdGereja(),
            'nama'        => $pengurus->getNama(),
            'jabatan'     => $pengurus->getJabatan(),
            'foto'        => $pengurus->getFoto(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/pengurus/add_pengurus", name="admin_add_pengurus", methods={"POST"})
     */
    // untuk simpan data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $gambar = $post->files->get('inpfoto');

                // untuk mengecek apa bila terdapat gambar
                if ($gambar) {
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    try {
                        $gambar->move(
                            $this->getParameter('folder_akun'),
                            $namaFile
                        );

                        $idu = $this->getUser()->id_users;

                        $pengurus = new TbPengurus();
                        $pengurus->setIdPengurus($this->myfun->getIdOtomatis('tb_pengurus'));
                        $pengurus->setIdGereja($idu);
                        $pengurus->setNama($post->request->get('inpnama'));
                        $pengurus->setJabatan($post->request->get('inpjabatan'));
                        $pengurus->setFoto($namaFile);
                        $pengurus->setIns(date_create());
                        $pengurus->setUpd(date_create());

                        $this->mng->persist($pengurus);
                        $this->mng->flush();

                        $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data', 'type' => 'success', 'button' => 'Ok!'];
                    } catch (Exception $th) {
                        $response = ['title' => 'Gagal!', 'text' => 'Gagal tambah data', 'type' => 'error', 'button' => 'Ok!'];
                    }
                } else {
                    $response = ['title' => 'Gagal!', 'text' => 'Tidak ada!', 'type' => 'error', 'button' => 'Ok!'];
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
     * @Route("/admin/pengurus/upd_pengurus", name="admin_upd_pengurus", methods={"POST"})
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        // untuk mengecek apa bila terdapat request
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek apa bila token sesuai
            if ($this->isCsrfTokenValid('add', $token)) {
                $id = $post->request->get('inpidpengurus');
                $pengurus = $this->mng->getRepository(TbPengurus::class)->findOneBy(['id_pengurus' => $id]);

                $ubah_gambar = $post->request->get('ubah_gambar');

                try {
                    // untuk mengecek apa bila terjadi perubahan data
                    if ($ubah_gambar == 'on') {
                        $gambar   = $post->files->get('inpfoto');
                        $namaFile = uniqid() . '.' . $gambar->guessExtension();

                        if ($pengurus->getFoto() != null) {
                            // untuk menghapus foto
                            unlink($this->getParameter('folder_akun') . "/" . $pengurus->getFoto());
                        }

                        $gambar->move(
                            $this->getParameter('folder_akun'),
                            $namaFile
                        );

                        $pengurus->setNama($post->request->get('inpnama'));
                        $pengurus->setJabatan($post->request->get('inpjabatan'));
                        $pengurus->setFoto($namaFile);
                        $pengurus->setUpd(date_create());
                    } else {
                        $pengurus->setNama($post->request->get('inpnama'));
                        $pengurus->setJabatan($post->request->get('inpjabatan'));
                        $pengurus->setUpd(date_create());
                    }

                    $this->mng->persist($pengurus);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $th) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal tambah data', 'type' => 'error', 'button' => 'Ok!'];
                }
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/pengurus/del_pengurus", name="admin_del_pengurus", methods={"POST"})
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $pengurus = $this->mng->getRepository(TbPengurus::class)->findOneBy(['id_pengurus' => $id]);

            if ($pengurus->getFoto() != null) {
                // untuk menghapus foto
                unlink($this->getParameter('folder_akun') . "/" . $pengurus->getFoto());
            }

            $this->mng->remove($pengurus);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
