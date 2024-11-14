<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Cars;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
// use Symfony\Component\Form\Extension\Core\Type\UrlType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\Extension\Core\Type\MoneyType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\Extension\Core\Type\IntegerType;
// use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CarType extends AbstractType
{
    /**
     * Formulaire de création/modification d'une voiture
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model')
            ->add('year')
            ->add('price')
            ->add('mileage')
            ->add('description')
            ->add('coverImage')
            // ->add('slug') - le slug est généré automatiquement et pas modifiable
            ->add('owner')
            ->add('engine')
            ->add('horse')
            ->add('fuel')
            ->add('transmission')
            ->add('options')
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // Needed to support the correct handling of the collection
                'prototype' => true, // Enables prototype feature
                'prototype_name' => '__name__', // Placeholder for JavaScript
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}
