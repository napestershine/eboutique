<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Type the name for new product',
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ])
            ->add('price', TextType::class, [
                'attr' => [
                    'pattern' => '\d',
                    'placeholder' => 'Type the price e.g: 1000 for new product',
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ])
            ->add('brand', TextType::class, [
                'attr' => [
                    'placeholder' => 'Type the brand of new product',
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ])
            ->add('quantity', TextType::class, [
                'attr' => [
                    'pattern' => '\d',
                    'placeholder' => 'Type the quantity of new product',
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'title',
                'attr' => [
                    'placeholder' => 'Type the quantity of new product',
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_form';
    }
}
