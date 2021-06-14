<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName',TextType::class,[
                'label' => 'Name :',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])

            ->add('productQte',IntegerType::class,[
                'label' => 'Quantity :',
                'row_attr' =>[
                    'class' => 'input-group'
                ]
            ])
            ->add('productDiscount',IntegerType::class,[
                'label' => 'Discount :',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])

            ->add('productPrice',IntegerType::class,[
                'label' => 'price :',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('productDescription',TextareaType::class,[
                'label' => 'Description :',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('category')
            ->add('productPhoto', FileType::class , [
                    'label' => 'Photo',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false, ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
