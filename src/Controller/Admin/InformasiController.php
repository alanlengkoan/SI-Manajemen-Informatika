<?php

namespace App\Controller\Admin;

use App\Entity\TbInformasi;
use App\Entity\TbKategori;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InformasiController extends AbstractController
{

    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/admin/informasi", name="admin_informasi")
     */
    // fungsi default
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman'  => 'Informasi Informasi',
            'kategori' => $this->mng->getRepository(TbKategori::class)->getAll(),
            'data'     => $this->mng->getRepository(TbInformasi::class)->getRincian($idu),
        ];

        return $this->render('admin/informasi/view.html.twig', $data);
    }

    /**
     * @Route("/admin/informasi/add", name="admin_informasi_add")
     */
    // fungsi untuk tambah data
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
                        $gambar->move(
                            $this->getParameter('folder_informasi'),
                            $namaFile
                        );

                        $idu = $this->getUser()->id_users;

                        $informasi = new TbInformasi();
                        $informasi->setIdInformasi($this->myfun->getIdOtomatis('tb_informasi'));
                        $informasi->setIdGereja($idu);
                        $informasi->setIdKategori($post->request->get('inpidkategori'));
                        $informasi->setJudul($post->request->get('inpjudul'));
                        $informasi->setIsi($post->request->get('inpisi'));
                        $informasi->setGambar($namaFile);
                        $informasi->setStatus('1');
                        $informasi->setStatusGaleri('0');
                        $informasi->setTanggalPublish(date_create());
                        $informasi->setIns(date_create());
                        $informasi->setUpd(date_create());

                        $this->mng->persist($informasi);
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
     * @Route("/admin/informasi/get", name="admin_informasi_get")
     */
    // untuk ambil data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $informasi = $this->mng->getRepository(TbInformasi::class)->findOneBy(['id_informasi' => $id]);

        $data = [
            'id_informasi' => $informasi->getIdInformasi(),
            'id_kategori'  => $informasi->getIdKategori(),
            'judul'        => $informasi->getJudul(),
            'isi'          => $informasi->getIsi(),
            'gambar'       => $informasi->getGambar(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/informasi/upd", name="admin_informasi_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        // untuk mengecek apa bila terdapat request
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek apa bila token sesuai
            if ($this->isCsrfTokenValid('add', $token)) {
                $kd = $post->request->get('inpidinformasi');
                $informasi = $this->mng->getRepository(TbInformasi::class)->findOneBy(['id_informasi' => $kd]);

                $ubah_gambar = $post->request->get('ubah_gambar');

                // untuk mengecek apa bila terjadi perubahan data
                if ($ubah_gambar == 'on') {
                    $gambar   = $post->files->get('inpgambar');
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    if ($informasi->getGambar() != null) {
                        // untuk menghapus foto
                        unlink($this->getParameter('folder_informasi') . "/" . $informasi->getGambar());
                    }

                    $gambar->move(
                        $this->getParameter('folder_informasi'),
                        $namaFile
                    );
                    $informasi->setIdKategori($post->request->get('inpidkategori'));
                    $informasi->setJudul($post->request->get('inpjudul'));
                    $informasi->setIsi($post->request->get('inpisi'));
                    $informasi->setGambar($namaFile);
                    $informasi->setUpd(date_create());
                } else {
                    $informasi->setIdKategori($post->request->get('inpidkategori'));
                    $informasi->setJudul($post->request->get('inpjudul'));
                    $informasi->setIsi($post->request->get('inpisi'));
                    $informasi->setUpd(date_create());
                }

                $this->mng->persist($informasi);
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
     * @Route("/admin/informasi/upd_status", name="admin_informasi_upd_status")
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
                    $informasi->setStatus('0');
                    $informasi->setUpd(date_create());
                } else {
                    $informasi->setStatus('1');
                    $informasi->setUpd(date_create());
                }

                $this->mng->persist($informasi);
                $this->mng->flush();

                $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah status!', 'type' => 'success', 'button' => 'Ok!'];
            } catch (Exception $th) {
                $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah status!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/informasi/del", name="admin_informasi_del")
     */
    // fungsi untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $informasi = $this->mng->getRepository(TbInformasi::class)->findOneBy(['id_informasi' => $id]);

            if ($informasi->getGambar() != null) {
                // untuk menghapus foto
                unlink($this->getParameter('folder_informasi') . "/" . $informasi->getGambar());
            }

            $this->mng->remove($informasi);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
