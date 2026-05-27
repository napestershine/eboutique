<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/cart', name: 'cart_list')]
    public function listAction(Request $request): Response
    {
        $basket = $request->getSession()->get('basket', []);
        if (!empty($basket)) {
            $count = array_count_values($basket);
            $products = $this->entityManager->getRepository(Product::class)->findBy(['id' => array_unique($basket)]);
        } else {
            $products = [];
            $count = [];
        }

        return $this->render('cart/list.html.twig', [
            'products' => $products,
            'count' => $count,
            'basket' => $basket,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'basket_add')]
    public function addAction(Request $request, $id): Response
    {
        $session = $request->getSession();
        $basket = $session->get('basket', []);
        $basket[] = $id;
        $session->set('basket', $basket);
        $this->addFlash('notice', 'Product added to cart');

        return $this->redirectToRoute('cart_list');
    }

    #[Route('/cart/remove/{id}', name: 'basket_remove')]
    public function removeAction(Request $request, $id): Response
    {
        $session = $request->getSession();
        $basket = $session->get('basket', []);
        $counts = array_count_values($basket);

        if (!empty($basket) && !empty($counts)) {
            foreach ($basket as $k => $itemId) {
                if ($itemId == $id && $counts[$itemId] > 1) {
                    $counts[$itemId]--;
                } elseif ($itemId == $id && $counts[$itemId] == 1) {
                    unset($basket[$k]);
                    $session->set('basket', $basket);
                }
            }
        }

        return $this->redirectToRoute('cart_list');
    }
}
