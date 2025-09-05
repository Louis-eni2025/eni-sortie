<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut',DateTimeType::class,[
                'widget' => 'single_text',
            ])
            ->add('dateLimiteInscription',DateTimeType::class,[
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie')
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])
            ->add('rue', TextType::class, [
                'mapped' => false,
                'disabled' => true,
                'required' => false,
            ])
            ->add('code_postal', TextType::class, [
                'mapped' => false,
                'disabled' => true,
                'required' => false,
            ])
            ->add('latitude', TextType::class, [
                'mapped' => false,
                'disabled' => true,
                'required' => false,
            ])
            ->add('longitude', TextType::class, [
                'mapped' => false,
                'disabled' => true,
                'required' => false,
            ]);




//                        ->add('etat', EntityType::class, [
//                'class' => Etat::class,
//                'choice_label' => 'libelle',
//            ])
//
//            ->add('organisateur', EntityType::class, [
//                'class' => Utilisateur::class,
//                'choice_label' => 'id',
//            ])
//            ->add('participants', EntityType::class, [
//                'class' => Utilisateur::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
