<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Type the name for new category',
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ])
            ->add('parent', EntityType::class, [
                'placeholder' => 'Choose parent category name',
                'class' => Category::class,
                'choice_label' => 'title',
                'required' => false,
                'attr' => [
                    'class' => 'form-control col-centered',
                    'id' => 'inputEmail3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'category_form';
    }
}
