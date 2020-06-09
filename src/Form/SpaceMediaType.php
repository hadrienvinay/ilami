<?php

namespace App\Form;

use App\Entity\SpaceMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class SpaceMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, array(
                    'attr'=>array('placeholder'=> 'Nébuleuse d\'Orion', 'class'=> 'form-control'),
                    'label' => 'Titre')
            )
            ->add('description',TextareaType::class, array(
                    'attr'=>array('placeholder'=> 'Nébuleuse observée pour la première fois en 1654 par Copernic...', 'class'=> 'form-control'),
                    'label' => 'Description')
            )
            ->add('picture', FileType::class, array(
                    'attr'=>array('label'=> 'Upload Picture', 'class'=> 'form-control'),
                    'label' => 'Photo (img file)',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,

                    'required' => true,
                
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                "image/png",
                                "image/jpeg",
                                "image/pjpeg"
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ])
                    ],
                )
            );
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SpaceMedia::class,
        ]);
    }
}
