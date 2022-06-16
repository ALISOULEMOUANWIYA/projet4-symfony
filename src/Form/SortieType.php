<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateS', DateType::class, array('label'=> 'Date', 'attr'=>array('required','class'=>'form-control form-group')))
            ->add('qteS', TextType::class, array('label'=> 'Quantité', 'attr'=>array('required','class'=>'form-control form-group')))
            ->add('produit', EntityType::class, array('class'=>Produit::class ,'label'=> 'Produit', 'attr'=>array('required','class'=>'form-control form-group')))
            ->add('Valider', SubmitType::class, array('label'=> 'Ajouter', 'attr'=>array('class'=>'btn btn-success form-group')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
