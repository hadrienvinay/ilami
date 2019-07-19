<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Nom', 'class'=> 'form-control'),
                    'label' => 'Nom')
            )
            ->add('prename', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Prénom','class'=> 'form-control'),
                    'label' => 'Prénom')
            )
            ->add('birthDate', DateType::class, array(
                'attr'=>array('class'=> 'form-group'),
                'label' => 'Date de naissance',
                'required' => false,
                'placeholder' => array(
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-100))
            )
            ->add('team', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Equipe','class'=> 'form-control'),
                    'label' => 'Team')
            )
        ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User'
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }



}
