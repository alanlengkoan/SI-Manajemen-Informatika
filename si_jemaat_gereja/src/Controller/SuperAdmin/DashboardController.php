<?php

namespace App\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/superadmin", name="superadmin")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Dashboard Super Admin',
        ];

        return $this->render('superadmin/dashboard/view.html.twig', $data);
    }
}
