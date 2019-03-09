<?php

namespace App\Form;

use App\Entity\SSPShow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('date')
            ->add('budget')
            ->add('ticketPrice')
            ->add('location')
            ->add('synopsis')
            ->add('pictureFile', VichImageType::class, ['required' => false, 'download_link' => false,  'delete_label' => 'Delete the existing image?'])
            ->add('ticketLink',  TextType::class, ['required' => false])
            ->add('endDate')
            ->add('status', ChoiceType::class, ['choices' => [
                "No Status" => "No Status", //form will not accept an empty string as a status.... no idea why -Nathan
                'Sold Out' => 'Sold Out',
                'Archived' => 'archived',
                'New' => 'New'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SSPShow::class,
        ]);
    }
}
