<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut',DateTimeType::class,[
                'widget' => 'single_text'])
            ->add('dateLimiteInscription',DateType::class,[
                'widget' => 'single_text'])
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie')
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'disabled' => true,
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])
            ->add('rue',TextType::class,[
                'mapped' => false,
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
