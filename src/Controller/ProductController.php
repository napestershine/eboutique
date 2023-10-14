<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="product_list")
     */
    public function listAction(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('Product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/add", name="add_product")
     */
    public function addAction(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entity = $form->getData();
                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->addFlash('success', 'Correctly saved');
            } catch (\Exception $error) {
                $this->addFlash('danger', "Something went wrong...probably there is a product with a given name");
                return $this->redirectToRoute('add_product');
            }

            return $this->redirectToRoute('product_list');
        }

        return $this->render('Product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show_product")
     */
    public function showAction($id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            $this->addFlash('danger', 'Unable to find the product in the database');
        }

        return $this->render('Product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_product")
     */
    public function editAction(Request $request, $id): Response
    {
        $entity = $this->entityManager->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entity = $form->getData();
                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->addFlash('success', 'Correctly saved');
            } catch (\Exception $error) {
                $this->addFlash('danger', "Something went wrong...can't save it");
                return $this->redirectToRoute('edit_product', ['id' => $id]);
            }

            return $this->redirectToRoute('product_list');
        }

        return $this->render('Product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove_product")
     */
    public function removeAction($id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            $this->addFlash('danger', 'Unable to find the product in the database');
        }

        try {
            $this->entityManager->remove($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Product removed successfully');
        } catch (\Exception $error) {
            $this->addFlash('danger', 'Unable to remove the product');
        }

        return $this->redirectToRoute('product_list');
    }
}
