<?php

namespace App\Form;

use App\Entity\Sortie;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom de la sortie : ',
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                'data' => new \DateTime(),
                'format' => 'ddMMMMyyyy',
                'html5' => false,
                'label' => 'Début de la sortie : ',
            ])
            ->add('dateLimiteInscription', DateTimeType::class,[
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
