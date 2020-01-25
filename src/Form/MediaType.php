<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, array(
                    'attr'=>array('placeholder'=> 'Hymne Ligue des Champions, Documentaire sur les suricates...', 'class'=> 'form-control'),
                    'label' => 'Titre')
            )
            ->add('link',TextType::class, array(
                    'attr'=>array('placeholder'=> 'https://youtube.com/watch?xyzabc', 'class'=> 'form-control'),
                    'label' => 'Lien')
            )
            ->add('type',ChoiceType::class, array(
                    'attr'=>array('placeholder'=> 'Nom', 'class'=> 'form-control'),
                    'choices' => array('Sciences 👨‍🔬' => 'sciences', 'Fun 😂' => 'fun', 'Sport ⚽' => 'sport', 'Autre 🙄' => 'other'),
                    'label' => 'Type')
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
