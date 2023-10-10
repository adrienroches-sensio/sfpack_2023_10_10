<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello', methods: ['GET'])]
    public function index(): Response
    {
        return new Response(
            content: <<<"HTML"
            <body>
                Hello !
            </body>
            HTML
        );
    }
}
