<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReservationType extends ApplicationType
{

    private $transformer;
    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateArrivee', TextType::class, $this->getConfiguration("Date d'arrivée","La date à laquelle vous comptez arriver"))
            ->add('dateDepart', TextType::class, $this->getConfiguration("Date de départ","La date à laquelle vous compter repartir"))
            ->add('commentaire', TextareaType::class, $this->getConfiguration(false,"Si vous avez un commentaire, n'hésitez pas à nous en faire part !",["required"=>false]))
        ;

        $builder->get('dateArrivee')->addModelTransformer($this->transformer);
        $builder->get('dateDepart')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'validation_groups' => [
                'Default',
                'front'
            ]
        ]);
    }
}
