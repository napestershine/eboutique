<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{
    /**
     * @Route("/categories")
     * @Template()
     */
    public function listAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/show")
     * @Template()
     */
    public function showAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/edit")
     * @Template()
     */
    public function editAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/remove")
     * @Template()
     */
    public function removeAction()
    {
        return array(
                // ...
            );    }

}
