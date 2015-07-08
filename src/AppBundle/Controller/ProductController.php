<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductController extends Controller
{
    /**
     * @Route("/")
     * @Template(":Product:list.html.twig")
     */
    public function listAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/add")
     * @Template(":Product:add.html.twig")
     */
    public function addAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/show")
     * @Template(":Product:show.html.twig")
     */
    public function showAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/edit")
     * @Template(":Product:edit.html.twig")
     */
    public function editAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/remove")
     * @Template(":Product:remove.html.twig")
     */
    public function removeAction()
    {
        return array(
                // ...
            );    }

}
