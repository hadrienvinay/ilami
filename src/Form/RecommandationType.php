<?php

namespace App\Form;

use App\Entity\Recommandation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RecommandationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array(
                    'attr'=>array('placeholder'=> 'Cristaaal Bar / Pizza Hut', 'class'=> 'form-control'),
                    'label' => 'Nom')
            )
            ->add('address',TextType::class, array(
                    'attr'=>array('placeholder'=> 'Paris', 'class'=> 'form-control'),
                    'label' => 'Lieu')
            )
            ->add('type',ChoiceType::class, array(
                    'attr'=>array('placeholder'=> 'Nom', 'class'=> 'form-control'),
                    'choices' => array('Bar' => 'bar', 'Night Club' => 'club', 'restaurant' => 'restaurant'),
                    'label' => 'Type')
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recommandation::class,
        ]);
    }
}
