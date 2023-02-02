<?php

namespace App\Controller\Admin;

use App\Entity\TbProfil;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/admin/profil", name="admin_profil")
     */
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman' => 'Profil',
            'data'    => $this->mng->getRepository(TbProfil::class)->getRincian($idu),
        ];

        return $this->render('admin/profil/view.html.twig', $data);
    }

    /**
     * @Route("/admin/profil/get", name="admin_profil_get")
     */
    // untuk ambil data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $profil = $this->mng->getRepository(TbProfil::class)->findOneBy(['id_profil' => $id]);

        $data = [
           'id'        => $profil->getId(),
           'id_profil' => $profil->getIdProfil(),
           'id_gereja' => $profil->getIdGereja(),
           'judul'     => $profil->getJudul(),
           'isi'       => $profil->getIsi(),
           'gambar'    => $profil->getGambar(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/profil/add", name="admin_profil_add")
     */
    // untuk tambah data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $gambar = $post->files->get('inpgambar');

                // untuk mengecek apa bila terdapat gambar
                if ($gambar) {
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    // untuk mengecek apa bila proses simpan berhasil
                    try {
                        $idu = $this->getUser()->id_users;
                        
                        $gambar->move(
                            $this->getParameter('folder_profil'),
                            $namaFile
                        );

                        $profil = new TbProfil();
                        $profil->setIdProfil($this->myfun->getIdOtomatis('tb_profil'));
                        $profil->setIdGereja($idu);
                        $profil->setJudul($post->request->get('inpjudul'));
                        $profil->setIsi($post->request->get('inpisi'));
                        $profil->setGambar($namaFile);
                        $profil->setIns(date_create());
                        $profil->setUpd(date_create());

                        $this->mng->persist($profil);
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
     * @Route("/admin/profil/upd", name="admin_profil_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        // untuk mengecek apa bila terdapat request
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek apa bila token sesuai
            if ($this->isCsrfTokenValid('add', $token)) {
                $id = $post->request->get('inpidprofil');
                $profil = $this->mng->getRepository(TbProfil::class)->findOneBy(['id_profil' => $id]);

                $ubah_gambar = $post->request->get('ubah_gambar');

                // untuk mengecek apa bila terjadi perubahan data
                if ($ubah_gambar == 'on') {
                    $gambar   = $post->files->get('inpgambar');
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    if ($profil->getGambar() != null) {
                        // untuk menghapus foto
                        unlink($this->getParameter('folder_profil') . "/" . $profil->getGambar());
                    }

                    $gambar->move(
                        $this->getParameter('folder_profil'),
                        $namaFile
                    );

                    $profil->setJudul($post->request->get('inpjudul'));
                    $profil->setIsi($post->request->get('inpisi'));
                    $profil->setGambar($namaFile);
                    $profil->setUpd(date_create());
                } else {
                    $profil->setJudul($post->request->get('inpjudul'));
                    $profil->setIsi($post->request->get('inpisi'));
                    $profil->setUpd(date_create());
                }

                $this->mng->persist($profil);
                $this->mng->flush();

                $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/profil/del", name="admin_profil_del")
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $profil = $this->mng->getRepository(TbProfil::class)->findOneBy(['id_profil' => $id]);

            if ($profil->getGambar() != null) {
                // untuk menghapus foto
                unlink($this->getParameter('folder_profil') . "/" . $profil->getGambar());
            }

            $this->mng->remove($profil);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
