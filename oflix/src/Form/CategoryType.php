<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                "label" => "Nom de la catégorie",
                'help' => 'de 4 à 20 caractères.',
                'attr' => ['class' => 'ajoutdeclassname'],
            ])
            ->add('createdAt', null, [
                "label" => "Date de création",
                'widget' => 'single_text',
            ])
            ->add('ajouter', SubmitType::class); //on l'a mi dans twig
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Category::class,
    //     ]);
    // }
}
