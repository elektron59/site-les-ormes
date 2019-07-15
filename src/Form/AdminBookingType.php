<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\MobilHome;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateArrivee', DateType::class, [
                'widget'=> 'single_text'
            ])
            ->add('dateDepart', DateType::class, [
                'widget'=> 'single_text'
            ])
            ->add('commentaire')
            ->add('client', EntityType::class, [
                'class' => User::class,
                'choice_label' => function($user){
                    return $user->getPrenom() . " " . strtoupper($user->getNom());
                }
            ])
            ->add('annonce', EntityType::class,[
                'class' => MobilHome::class,
                'choice_label' => 'nomMh '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
