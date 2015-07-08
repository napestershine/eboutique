<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{
    /**
     * @Route("/categories")
     * @Template(":Category:list.html.twig")
     */
    public function listAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/category/add")
     * @Template(":Category:add.html.twig")
     */
    public function addAction()
    {
        return array(
            // ...
        );    }

    /**
     * @Route("/category/show")
     * @Template(":Category:show.html.twig")
     */
    public function showAction()
    {
        return array(
            // ...
        );    }

    /**
     * @Route("/category/edit")
     * @Template(":Category:edit.html.twig")
     */
    public function editAction()
    {
        return array(
            // ...
        );    }

    /**
     * @Route("/category/remove")
     * @Template(":Category:remove.html.twig")
     */
    public function removeAction()
    {
        return array(
            // ...
        );    }

}
