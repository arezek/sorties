<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Positive;


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

                'label' => 'Date : ',
                'format' => 'ddMMMMyyyy',
                'html5' => false,
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [

                'label' => 'Clôturée le : ',
            ])
            ->add('duree', TimeType::class, [

                'label' => 'Durée : ',
            ])
            ->add(
                'nbInscriptionsMax',
                NumberType::class,
                [
                    'attr' => ['placeholder' => 'Nombre limite de participant'],
                    'label' => false,
                    'constraints' => [new Positive()]
                ]
            )
            ->add('infosSortie', TextType::class, [
                'attr' => ['placeholder' => 'Description de l\'évènement'],
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
