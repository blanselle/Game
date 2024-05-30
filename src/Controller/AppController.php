<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AppController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home()
    {
        return new Response ($this->renderView('app/home.html.twig'));
    }
}
