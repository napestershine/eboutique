<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/categories', name: 'category_list')]
    public function listAction(): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        if (!$categories) {
            $this->addFlash('danger', 'Unable to find categories in the database');
        }

        return $this->render('Category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/add', name: 'add_category')]
    public function addAction(Request $request): Response
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $category = $form->getData();
                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->addFlash('success', 'Category successfully created');
            } catch (\Exception $error) {
                $this->addFlash('danger', 'Something went wrong, probably there is a category with the given name!');
                return $this->redirectToRoute('add_category');
            }

            return $this->redirectToRoute('category_list');
        }

        return $this->render('Category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/show/{id}', name: 'show_category')]
    public function showAction($id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            $this->addFlash('danger', 'Unable to find this category');
            return $this->redirectToRoute('category_list');
        }

        return $this->render('Category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category/edit/{id}', name: 'edit_category')]
    public function editAction(Request $request, $id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            $this->addFlash('danger', 'Unable to find categories in the database');
            return $this->redirectToRoute('category_list');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $category = $form->getData();
                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->addFlash('success', 'Successfully saved');
                return $this->redirectToRoute('category_list');
            } catch (\Exception $error) {
                $this->addFlash('danger', 'Something went wrong. Please try again later!');
                return $this->redirectToRoute('edit_category', ['id' => $id]);
            }
        }

        return $this->render('Category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/remove/{id}', name: 'remove_category')]
    public function removeAction($id): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            $this->addFlash('danger', 'Unable to find categories in the database');
            return $this->redirectToRoute('category_list');
        }

        try {
            $this->entityManager->remove($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Category removed successfully');
        } catch (\Exception $error) {
            $this->addFlash('danger', 'Unable to remove category');
        }

        return $this->redirectToRoute('category_list');
    }
}
