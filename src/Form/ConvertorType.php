<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ConvertorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url',TextType::class, array(
                    'attr'=>array('placeholder'=> 'https://youtube.com/cjxkcsdhsd', 'class'=> 'form-control'),
                    'label' => 'Youtube To MP3')
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
