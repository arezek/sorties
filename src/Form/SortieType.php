<?php

namespace App\Form;

use App\Entity\Sortie;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class,[
                'data' => new \DateTime(),
                'format' => 'ddMMMMyyyy',
                'html5' => false,



            ])
            ->add('duree', TimeType::class)
            ->add('dateLimiteInscription', DateTimeType::class,[
                'data' => new \DateTime(),
            ])
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
