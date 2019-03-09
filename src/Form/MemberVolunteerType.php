<?php

namespace App\Form;

use App\Entity\MemberVolunteer;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberVolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('age', NumberType::class, ['required' => false])
            ->add('volunteerOptions', ChoiceType::class, array(
                'choices' =>array(
                    'Set Design' =>'setDesign',
                    'Set Construction' =>'setConstruction',
                    'Set Painting' =>'setPainting',
                    'Makeup and Hair' =>'makeupAndHair',
                    'Stage Crew' =>'stageCrew'
                ),
                'multiple' => true,
                'expanded' => true
            ))
            ->add('Other', TextType::class, ['mapped' => false,'required' => false])
            ->add('additionalInfo',TextareaType::class, ['required' => false])
            ->add('Submit', SubmitType::class,['label' => 'Volunteer!', 'attr' => ['class' => 'btn btn-danger']]);
//            ->add('user_name', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MemberVolunteer::class,
        ]);
    }
}
