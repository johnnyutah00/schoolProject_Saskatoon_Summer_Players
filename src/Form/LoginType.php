<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', EmailType::class, array(
                'attr' => array(
                    'maxlength' => 256
                )
            ))
            ->add('_password', PasswordType::class, array(
                'attr' => array(
                    'maxlength' => 4096
                )
            ))
            ->add('_remember_me', CheckboxType::class, array('required' => false))
            ->add('login', SubmitType::class, ['label' => 'Login'])
        ;
    }

    /**
     * This function is to prevent Symfony from changing the input names requested above
     * by enclosing them in login[...]
     * https://stackoverflow.com/questions/50082673/symfony-4-login-form-with-security-and-database-users
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return "";
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
