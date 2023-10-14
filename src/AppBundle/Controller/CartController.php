<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * @Route("/cart" , name="cart_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $basket = $request->getSession()->get('basket');
        if(!empty($basket)){
            $count = array_count_values($basket); //quantity of each products
            //to avoid repeated products use array_unique
            $products = $this->getDoctrine()->getRepository('AppBundle\Entity\Product')->findBy(array('id' => array_unique($basket)));

        }else{
            $products = array();
            $count = array();
        }



        return $this->render(':Cart:list.html.twig',array(
            'products'=>$products,
            'count'=>$count,
            'basket'=>$basket
        ));
    }

    /**
     * @Route("/cart/add/{id}" ,name="basket_add")
     * @Template()
     */
    public function addAction(Request $request,$id)
    {
        $session = $request->getSession();

        $basket = $session->get('basket', array());
        $basket[] = $id;
        $session->set('basket', $basket);
        $this->addFlash('notice', 'Produkt został dodany do koszyka');

        return $this->redirect($this->generateUrl('cart_list'));

    }

    /**
     * @Route("/cart/remove/{id}", name="basket_remove")
     * @Template()
     */
    public function removeAction(Request $request,$id)
    {

        $session = $request->getSession();
        $basket = $session->get('basket', array());
        $counts = array_count_values($basket); //quantity of each products


        if(!empty($basket) && !empty($counts)){

            foreach($basket as $k => $itemId){
                if($itemId == $id && $counts[$itemId]>1) { //if there is more than 1 element in basket with this name

                    $counts[$itemId]--; //lower quantity
                }
                elseif($itemId == $id && $counts[$itemId]==1){

                    unset($basket[$k]); //remove item
                    $session->set('basket',$basket); //set new content of basket

                }
            }
        }
        return $this->redirect($this->generateUrl('cart_list'));

    }
}
