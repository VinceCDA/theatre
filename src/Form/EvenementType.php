<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('DateDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('DateFin', null, [
                'widget' => 'single_text',
            ])
            ->add('Lieu')
            ->add('Capacite')
            // ->add('CreatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('Auteur', EntityType::class, [
            //     'class' => Utilisateur::class,
            //     'choice_label' => 'id',
            // ])
            ->add('Categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'Nom',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
