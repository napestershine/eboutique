<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
}
