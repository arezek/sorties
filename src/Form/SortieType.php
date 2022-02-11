<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Nom de l\'évènement'],
                    'label' => false
                ]
            )
            ->add('dateHeureDebut', DateTimeType::class, [
                'data' => new \DateTime(),
                'format' => 'ddMMMMyyyy',
                'html5' => false,
            ])
            ->add('duree', TimeType::class)
            ->add('dateLimiteInscription', DateTimeType::class, [
                'data' => new \DateTime(),
                'label' => 'Fin des inscriptions : ',
            ])
            ->add('duree', TimeType::class, [
                'label' => 'Durée : ',
            ])
            ->add('nbInscriptionsMax', null, [
                'label' => 'Nombre de participants max : ',
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Informations : ',
            ])
            ->add(
                'nbInscriptionsMax',
                NumberType::class,
                [
                    'attr' => ['placeholder' => 'Nombre limite de participant'],
                    'label' => false
                ]
            )
            ->add('infosSortie', TextType::class, [
                'attr' => ['placeholder' => 'Description de l\'évènement'],
                'label' => false
            ])
            ->add('etat', TextType::class, [
                'attr' => ['placeholder' => 'État de votre Sortie'],
                'label' => false
            ])
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
