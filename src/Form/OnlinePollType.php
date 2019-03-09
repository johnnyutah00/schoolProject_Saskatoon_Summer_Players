<?php

namespace App\Form;

use App\Entity\OnlinePoll;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OnlinePollType extends AbstractType
{
    //Build the new OnlinePoll form
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Add fields for name, description and options
        $builder
            ->add('Name', TextType::class, array(
                'required' => true
            ))
            ->add('Description', TextareaType::class, array(
                'required' => true
            ))
            ->add('Options', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OnlinePoll::class,
        ]);
    }
}
