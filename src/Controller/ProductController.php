<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("/" , name="product_list")
     */
    public function listAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle\Entity\Product')->findAll();

        return $this->render(':Product:list.html.twig',array(
           'products'=>$products
        ));

    }

    /**
     * @Route("/add", name="add_product")
     * @Template(":Product:add.html.twig")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new ProductType());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $entity = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash('success','Correctly saved');

            }catch(\Exception $error)
            {
                $this->addFlash('danger',"Something went wrong...probably there is a product with given name");
                return $this->redirect($this->generateUrl('add_product'));

            }
//            var_dump($routeIfSuccess);die;
            return $this->redirect($this->generateUrl('product_list'));

        }


        return $this->render(':Product:add.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/show/{id}" , name="show_product")
     * @Template("")
     */
    public function showAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle\Entity\Product')->find($id);

        if(!$product){
            $this->addFlash('danger','Unable to find product in database');
        }

        return $this->render(':Product:show.html.twig',array(
            'product'=>$product
        ));

    }

    /**
     * @Route("/edit/{id}" , name="edit_product")
     * @Template(":Product:edit.html.twig")
     */
    public function editAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle\Entity\Product')->find($id);

        $form = $this->createForm(new ProductType(),$entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $entity = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash('success','Correctly saved');

            }catch(\Exception $error)
            {
                $this->addFlash('danger',"Something went wrong...can't save it");
                return $this->redirect($this->generateUrl('edit_product'));

            }
//            var_dump($routeIfSuccess);die;
            return $this->redirect($this->generateUrl('product_list'));

        }

        return $this->render(':Product:edit.html.twig',array(
            'form'=>$form->createView()
        ));

    }

    /**
     * @Route("/remove/{id}", name="remove_product")
     * @Template(":Product:remove.html.twig")
     */
    public function removeAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle\Entity\Product')->find($id);

        if(!$product){
            $this->addFlash('danger','Unable to find product in database');
        }

        try{
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', 'Product removed successfully');

        }catch(\Exception $error){
            $this->addFlash('danger','Unable to remove product');
        }

        return $this->redirect($this->generateUrl('product_list'));

    }



}
