<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_list")
     */
    public function listAction(Request $request)
    {
        $basket = $request->getSession()->get('basket', []);
        if (!empty($basket)) {
            $count = array_count_values($basket); // Quantity of each product
            // To avoid repeated products, use array_unique
            $products = $this->getDoctrine()->getRepository('App\Entity\Product')->findBy(['id' => array_unique($basket)]);
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

    /**
     * @Route("/cart/add/{id}", name="basket_add")
     */
    public function addAction(Request $request, $id)
    {
        $session = $request->getSession();
        $basket = $session->get('basket', []);
        $basket[] = $id;
        $session->set('basket', $basket);
        $this->addFlash('notice', 'Produkt został dodany do koszyka');

        return $this->redirectToRoute('cart_list');
    }

    /**
     * @Route("/cart/remove/{id}", name="basket_remove")
     */
    public function removeAction(Request $request, $id)
    {
        $session = $request->getSession();
        $basket = $session->get('basket', []);
        $counts = array_count_values($basket); // Quantity of each product

        if (!empty($basket) && !empty($counts)) {
            foreach ($basket as $k => $itemId) {
                if ($itemId == $id && $counts[$itemId] > 1) { // If there is more than 1 element in the basket with this name
                    $counts[$itemId]--; // Lower quantity
                } elseif ($itemId == $id && $counts[$itemId] == 1) {
                    unset($basket[$k]); // Remove item
                    $session->set('basket', $basket); // Set the new content of the basket
                }
            }
        }

        return $this->redirectToRoute('cart_list');
    }
}
