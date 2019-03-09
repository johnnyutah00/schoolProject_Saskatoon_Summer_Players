<?php

namespace App\Form;

use App\Entity\AuditionDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditionDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playTitle')
            ->add('playImage')
            ->add('auditionDetails')
            ->add('directorMessage')
            ->add('howToAudition')
            ->add('synopsis')
            ->add('characterSummeries')
            ->add('noteFromDirector')
            ->add('auditionMaterials')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AuditionDetails::class,
        ]);
    }
}
