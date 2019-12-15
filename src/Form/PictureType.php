<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
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
            ->add('address', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Adresse', 'class'=> 'form-control'),
                    'label' => 'ou c?')
            )
            ->add('startdate', DateTimeType::class, array(
                    'attr'=>array('class'=> 'form-group'),
                    'label' => 'Début de l évènement')
            )
            ->add('enddate', DateTimeType::class, array(
                    'attr'=>array('class'=> 'form-group'),
                    'label' => 'Fin de l évènement')
            )
            ->add('description', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Description','class'=> 'form-control'),
                    'label' => 'Bla bla blou')
            )
        ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Event'
        ));
    }





}
