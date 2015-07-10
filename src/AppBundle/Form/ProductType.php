<?php
/**
 * Created by PhpStorm.
 * User: rafal
 * Date: 10.07.15
 * Time: 08:38
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name','text',array(
                        'attr'=>array(
                            'placeholder'=>'Type the name for new product',
                            'class'=>'form-control col-centered',
                            'id'=>'inputEmail3'
                )))
                ->add('price','text',array(
                    'attr'=>array(
                        'pattern'=>'\d',
                        'placeholder'=>'Type the price e.g: 1000 for new product',
                        'class'=>'form-control col-centered',
                        'id'=>'inputEmail3'
                    )))
                ->add('brand','text',array(
                    'attr'=>array(
                        'placeholder'=>'Type the brand of new product',
                        'class'=>'form-control col-centered',
                        'id'=>'inputEmail3'
                    )))
                ->add('quatity','text',array(
                    'attr'=>array(
                        'pattern'=>'\d',
                        'placeholder'=>'Type the quantity of new product',
                        'class'=>'form-control col-centered',
                        'id'=>'inputEmail3'
                    )))
                ->add('category','entity',array(
                    'required'=>true,
                    'class'=>'AppBundle\Entity\Category',
                    'property'=>'title',
                    'attr'=>array(

                        'placeholder'=>'Type the quantity of new product',
                        'class'=>'form-control col-centered',
                        'id'=>'inputEmail3'
                    )));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>'AppBundle\Entity\Product'
        ));
    }


    public function getName()
    {
        return 'product_form';
    }
}