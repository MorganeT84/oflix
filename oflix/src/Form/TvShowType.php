<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\TvShow;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                "label" => "Title",
                // il est possible d'ajouter des contraintes directement dans l'objet de formulaire
                // mais il est recommandé de les mettre au niveau de l'entité
                // [
                //     'constraints' => [
                //         new NotBlank(),
                //     ]
                // ]
            ])
            ->add('synopsis', null, [
                "label" => "Synopsis",
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
            ])
            ->add('nbLikes', null, [
                "label" => "Nb likes",
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Categories',
                'expanded' => true,
                'multiple' => true,
            ])
            // ->add('rolePlays')
        ;;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}
