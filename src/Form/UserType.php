<?php

namespace App\Form;

use App\Entity\User;
use App\Enumeration\RoleList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' =>'Email'
                ]
            ])
            ->add('roles', CollectionType::class, [
                'label' => 'Roles',
                'attr' => [
                    'data-list-selector' => 'countries'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => false,
                    'choices' => [
                        'User' => 'ROLE_USER',
                        'Banned' => 'ROLE_BANNED',
                        'Stats' => 'ROLE_STATS',
                        'Admin' => 'ROLE_ADMIN',
                    ]
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' =>'Mot de passe'
                ]
            ] )
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' =>'Prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' =>'Nom'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'required' => false,
                'attr' => [
                    'placeholder' =>'Pseudo'
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone'
                ]
            ])
            ->add('birthAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('registeredAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
