<?php

namespace App\Form;

use App\Form\ImageType;
use App\Entity\MobilHome;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nomMh', 
                TextType::class, 
                $this->getConfiguration("Nom", "Tapez un nom pour ce Mobilhome")
                )

            ->add(
                'slugMh', 
                TextType::class, 
                $this->getConfiguration("Adresse Web", "Tapez l'adresse Web (automatique", [
                    'required' => false
                ])
                )

            ->add('presentationMh', 
            TextareaType::class, 
            $this->getConfiguration("Présentation", "Tapez un texte de présentation du mobil home")
            )

            ->add('detailMh', 
            TextareaType::class, 
            $this->getConfiguration("Détail", "Détail des équipements du mobil home")
            )

            ->add('emplacementMh',
            TextType::class, 
            $this->getConfiguration("Emplacement", "Tapez le nom ou le numéro de la parcelle")
            )

            ->add('anneeMh',
            IntegerType::class, 
            $this->getConfiguration("Année", "Tapez l'année de fabrication du mobil home")
            )

            ->add('surfaceMh',
            IntegerType::class, 
            $this->getConfiguration("Surface du mobil home", "Tapez la surface du mobil home")
            )

            ->add('surfaceEmplacementMh',
            IntegerType::class, 
            $this->getConfiguration("Surface emplacement", "Tapez la surface de l'emplacement du mobil home")
            )

            ->add('nbChambreMh',
            IntegerType::class, 
            $this->getConfiguration("Nb Chambre", "Tapez le nombre de chambre du mobil home")
            )

            ->add('nbPersonneMh',
            IntegerType::class, 
            $this->getConfiguration("Nb personnes", "Tapez le nombre de personne que peut accueillir le mobil home")
            )

            ->add('photoPrincipaleMh', 
            TextType::class, 
            $this->getConfiguration("Image principale", "Tapez l'adresse Web de l'image principale")
            )

            ->add('tarifJourHsMh', 
            MoneyType::class, 
            $this->getConfiguration("Tarif/Nuit HS", "Tapez le tarif pour une nuit en haute saison")
            )

            ->add('tarifSemaineHsMh', 
            MoneyType::class, 
            $this->getConfiguration("Tarif/Semaine HS", "Tapez le tarif pour une semaine en haute saison")
            )
            ->add('tarifJourBsMh', 
            MoneyType::class, 
            $this->getConfiguration("Tarif/Nuit BS", "Tapez le tarif pour une nuit en basse saison")
            )

            ->add('tarifSemaineBsMh', 
            MoneyType::class, 
            $this->getConfiguration("Tarif/Semaine BS", "Tapez le tarif pour une semaine en basse saison")
            )

            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true, //Permet de préciser si l'on a le droit d'ajouter de nouveaux éléments
                    'allow_delete' => true // Permet de supprimer des éléments liés
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MobilHome::class,
        ]);
    }
}
