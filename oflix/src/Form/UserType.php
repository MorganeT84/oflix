<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pseudo', null, [
            "label" => "Pseudo",
        ])
        ->add('email', null, [
            "label" => "Email",
        ])
        ->add('roles', ChoiceType::class, [
            "label" => "Roles",
            'choices' => [
                'Admin' => 'ROLE_ADMIN',
                'User' => 'ROLE_USER',
            ],
            'multiple' => true,
            'expanded' => true,
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class, 
            'required' => true,

            // comme on veut appliquer des règles de gestion non standard
            // on précise à symfony que cette valeur ne correspond à aucun 
            // champ de notre objet
            //!\ il faudra gérer la valeur saisie dans le controleur
            'mapped' => false,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ]);
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
