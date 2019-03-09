<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class MemberUpdateType extends AbstractType
{
    /** This function will build our form object for member registration page
     * that will be passed into the template
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('memberType', HiddenType::class, array(
//                'choices' => array(
//                    'Individual'=> 'Individual',
//                    'Family' => 'Family',
//                ),
            ))
            ->add('memberOption', HiddenType::class, array(
//                'choices' => array(
//                    '1-year Membership'=> '1-year Paid Membership',
//                    'Auto renew 1-year Membership'=> 'Subscription',
//                ),
            ))
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('userName', EmailType::class,array(
                'label' => 'Email'
            ))
            ->add('password', HiddenType::class, array(
//                'type' => HiddenType::class,
//                'invalid_message' => 'Passwords must match.',
//                'options' => array('attr' => array('class' => 'password-field')),
//                'required' => true,
//                'first_options'  => array('label' => 'Password'),
//                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('membershipAgreement', HiddenType::class, array('required' => true))
            ->add('addressLineOne', TextType::class)
            ->add('addressLineTwo', TextType::class, array(
                'required' => false,
            ))
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('province', ChoiceType::class, array(
                'choices' =>array(
                    'Please Enter a Province' =>'NA',
                    'Alberta' => 'AB',
                    'British Columbia' => 'BC',
                    'Manitoba' => 'MB',
                    'New Brunswick' => 'NB',
                    'Newfoundland and Labrador' => 'NL',
                    'Northwest Territories' => 'NT',
                    'Nova Scotia' => 'NS',
                    'Nunavut' => 'NU',
                    'Ontario' => 'ON',
                    'Prince Edward Island' => 'PE',
                    'Quebec' => 'QC',
                    'Saskatchewan' => 'SK',
                    'Yukon' => 'YT'
             )
            ))
            ->add('company', TextType::class, array(
                'required' => false,
                'label' => 'Company (Optional)'
            ))
            ->add('phone', TelType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => '(xxx) xxx-xxxx'
                ),
                'label' => 'Phone (Optional)'
            ))
            ->get('phone')
                ->addModelTransformer(new CallbackTransformer(
                    function ($phoneForm){
                        return $phoneForm;
                    },
                    function ($phoneDB)
                    {
                        return $phoneDB;
                    }
                ))

            ;
    }

    /**
     * This method will link our form to our class that holds the
     * underlying data when embedding forms
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
