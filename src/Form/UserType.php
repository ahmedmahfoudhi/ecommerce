<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class,[
                'label' => 'Email',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Firstname',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Lastname',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('phoneNumber',TextType::class,[
                'label' => 'Phone Number',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('state',TextType::class,[
                'label' => 'State',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('street',TextAreaType::class,[
                'label' => 'Street',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('postalCode',IntegerType::class,[
                'label' => 'Postal Code',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Firstname',
                'row_attr' => [
                    'class' => 'input-group'
                ]
            ])


            ->add("avatar",FileType::class,[
                "mapped" =>false,
                'row_attr' => ['class' => 'input-group']
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
