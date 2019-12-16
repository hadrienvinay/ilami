<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
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
            ->add('presentationPicture', FileType::class, array(
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
            )
        ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Album'
        ));
    }

}
