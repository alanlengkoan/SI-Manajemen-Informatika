<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Dashboard Admin',
        ];

        return $this->render('admin/dashboard/view.html.twig', $data);
    }
}
