<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, array('label' => 'Nom de la sortie : '))
            ->add('dateHeureDebut', null , array('label' => 'Date et heure de la sortie : '))
            ->add('dateLimiteInscription', null, array('label' => 'Date limite d \' inscription : '))
            ->add('nbInscriptionsMax', null , array('label' => 'Nombre de places : '))
            ->add('duree', null , array('label' => 'Durée : '))
            ->add('infosSortie', TextareaType::class , array('label' => 'Description et infos : '))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
