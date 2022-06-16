<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('id', TextType::class, array('label' => 'Id du produit', 'attr' => array( 'class' => 'form-control form-group lbP lblP')))
            ->add('lebelle', TextType::class, array('label' => 'Libelle', 'attr' => array('required', 'class' => 'form-control form-group lbP lblP')))
            ->add('qtStock', TextType::class, array('label' => 'Quantité', 'attr' => array('required', 'class' => 'form-control form-group lbP qtP')))
            ->add('Valider', SubmitType::class, array('label' => 'Ajouter', 'attr' => array('class' => 'btn btn-success form-group ajouterProduit')));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
