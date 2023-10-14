<?php
/**
 * Created by PhpStorm.
 * User: rafal
 * Date: 08.07.15
 * Time: 15:11
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategoryType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder
                ->add('title','text',array(
                    'attr'=>array(
                        'placeholder'=>'Type the name for new category',
                        'class'=>'form-control col-centered',
                        'id'=>'inputEmail3'
                    ),
                ))
                ->add('parent','entity',array(
                    'placeholder'=>'Choose parent category name',
                    'class'=>'AppBundle:Category',
                    'property'=>'title',
                    'required'=>false,
                    'attr'=>array(

                        'class'=>'form-control  col-centered',
                        'id'=>'inputEmail3'
                    )
                ))
// ->add('dueDate', null, array('mapped' => false))
// if we have extra fields (like "do you agree with these terms" checkbox) set mapped to false
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category',
        ));
    }


    public function getName(){
        return 'category_form';
    }
}