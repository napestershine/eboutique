<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="category_list")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle\Entity\Category')->findAll();

        if(!$categories){
            $this->addFlash('danger','Unable to find categories in database');
        }

        return $this->render(':Category:list.html.twig',array(
            'categories'=>$categories
        ));

    }

    /**
     * @Route("/category/add" ,name="add_category")
     * @Template(":Category:add.html.twig")
     */
    //if you extends controller...the base controller set Request as a parameter
    public function addAction(Request $request)
    {
        //you dont have to give 2nd param because of configureOptions function in form which give the obj
        $form = $this->createForm(new CategoryType());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try {
                $category = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'Category successfully created');

                }catch(\Exception $error){

                    $this->addFlash('danger','Something went wrong...probably there is a category with given name!');
                    return $this->redirect($this->generateUrl('add_category'));
                }
            return $this->redirect($this->generateUrl('category_list'));
        }


        return $this->render(':Category:add.html.twig',array(
            'form' => $form->createView()
        )    );
    }

    /**
     * @Route("/category/show/{id}" ,name="show_category")
     */
    public function showAction($id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle\Entity\Category')->find($id);

        if(!$category){
            $this->addFlash('danger','Unable to find this category');
        }

        return $this->render(':Category:show.html.twig',array(
            'category'=> $category
        ));
    }

    /**
     * @Route("/category/edit/{id}", name="edit_category")
     */
    public function editAction(Request $request,$id)
    {

        $category = $this->getDoctrine()->getRepository('AppBundle\Entity\Category')->find($id);

        if(!$category){
            $this->addFlash('danger','Unable to find categories in database');
        }

        $form = $this->createForm(new CategoryType(), $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $category = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                $this->addFlash('success','Successfully saved');
                return $this->redirect($this->generateUrl('category_list'));

            }catch(\Exception $error){
                $this->addFlash('danger', 'Something went wrong. Please try again later!');
                return $this->redirect($this->generateUrl('edit_category'));
            }
        }

        return $this->render(':Category:edit.html.twig',array(
            'form'=>$form->createView()
        ));


    }

    /**
     * @Route("/category/remove/{id}" ,name="remove_category")
     */
    public function removeAction($id)
    {

        $category = $this->getDoctrine()->getRepository('AppBundle\Entity\Category')->find($id);

        if(!$category){
            $this->addFlash('danger','Unable to find categories in database');
        }
        try{
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            $this->addFlash('success', 'Category removed successfully');
        }catch(\Exception $error){
            $this->addFlash('danger', 'Unable to remove category');

        }
        return $this->redirect($this->generateUrl('category_list'));
    }

}
