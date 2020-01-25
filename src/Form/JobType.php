<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName', TextType::class, array(
                    'attr'=>array('placeholder'=> 'EY / CGI / Alten vie', 'class'=> 'form-control'),
                    'label' => 'Entreprise')
            )
            ->add('address', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Adresse', 'class'=> 'form-control'),
                    'label' => 'ou c?')
            )
            ->add('startdate', DateType::class, array(
                    'attr'=>array('class'=> 'form-group'),
                    'label' => 'Début du job')
            )
            ->add('description', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Description','class'=> 'form-control'),
                    'label' => 'Bla bla blou')
            )
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Job'
        ));
    }





}
