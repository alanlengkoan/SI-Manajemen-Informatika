<?php

namespace App\Controller\SuperAdmin;

use App\Entity\User;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AkunController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/superadmin/akun", name="superadmin_akun")
     */
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman' => 'Akun',
            'data'    => $this->mng->getRepository(User::class)->getDetail($idu),
        ];

        return $this->render('superadmin/akun/view.html.twig', $data);
    }

    /**
     * @Route("/superadmin/akun/upd_foto", name="superadmin_upd_foto")
     */
    // untuk ubah foto akun
    public function upd_foto(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $gambar = $post->files->get('inpfotoakun');

                // untuk mengecek apa bila proses simpan berhasil
                try {
                    $idu    = $this->getUser()->id_users;
                    $user   = $this->mng->getRepository(User::class)->findOneBy(['id_users' => $idu]);

                    // untuk mengecek apa bila terdapat gambar
                    if ($gambar) {
                        $namaFile = uniqid() . '.' . $gambar->guessExtension();

                        if ($user->getFoto() != null) {
                            // untuk menghapus foto
                            unlink($this->getParameter('folder_akun') . "/" . $user->getFoto());
                        }

                        $gambar->move(
                            $this->getParameter('folder_akun'),
                            $namaFile
                        );

                        // untuk tabel user
                        $user->setFoto($namaFile);
                        $user->setUpd(date_create());
                    }

                    $this->mng->persist($user);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data!', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $e) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data!', 'type' => 'error', 'button' => 'Ok!'];
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
     * @Route("/superadmin/akun/upd_akun", name="superadmin_upd_akun", methods={"POST"})
     */
    // untuk ubah data akun
    public function upd_akun(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                // untuk mengecek apa bila proses simpan berhasil
                try {
                    $idu  = $this->getUser()->id_users;
                    $user = $this->mng->getRepository(User::class)->findOneBy(['id_users' => $idu]);
                    $user->setNama($post->request->get('inpnama'));
                    $user->setEmail($post->request->get('inpemail'));
                    $user->setJadwalIbadahOperasional($post->request->get('inpjadwalibadah'));
                    $user->setAlamat($post->request->get('inpalamat'));
                    $user->setTelepon($post->request->get('inptelepon'));
                    $user->setUsername($post->request->get('inpusername'));
                    $user->setUpd(date_create());

                    $this->mng->persist($user);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah akun!', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $e) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah akun!', 'type' => 'error', 'button' => 'Ok!'];
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
     * @Route("/superadmin/akun/upd_keamanan", name="superadmin_upd_keamanan", methods={"POST"})
     */
    // untuk ubah data keamanan
    public function upd_keamanan(Request $post, UserPasswordEncoderInterface $pass_enkrip)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $pwd_lama = $post->request->get('inppasswordlama');
                $pwd_baru = $post->request->get('inppasswordbaru');
                $konfirmasi_pwd_baru = $post->request->get('inpkonfirmasipassword');

                $idu  = $this->getUser()->id_users;
                $user = $this->mng->getRepository(User::class)->findOneBy(['id_users' => $idu]);
                $check_pwd = $pass_enkrip->isPasswordValid($user, $pwd_lama);

                if ($check_pwd === true) {
                    if ($pwd_baru == $konfirmasi_pwd_baru) {
                        try {
                            $user->setPassword($pass_enkrip->encodePassword($user, $pwd_baru));
                            $this->mng->persist($user);
                            $this->mng->flush();

                            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah password!', 'type' => 'success', 'button' => 'Ok!'];
                        } catch (Exception $e) {
                            $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah password!', 'type' => 'error', 'button' => 'Ok!'];
                        }
                    } else {
                        $response = ['title' => 'Peringatan!', 'text' => 'Password baru dan konfirmasi password baru tidak sama!', 'type' => 'warning', 'button' => 'Ok!'];
                    }
                } else {
                    $response = ['title' => 'Peringatan!', 'text' => 'Password lama yang Anda masukkan tidak sama!', 'type' => 'warning', 'button' => 'Ok!'];
                }
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
