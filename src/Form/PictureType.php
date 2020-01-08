<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\FilesType;
use App\Entity\Album;

class PictureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('files', FileType::class, array(
                    'attr' => array(
                        'label'=> 'Upload Picture',
                        'class'=> 'form-control',
                        'multiple' => true,
                        'accept' => 'image/*'
                        ),
                    'label' => 'Photo (img file)',
                    // unmapped means that this field is not associated to any entity property
                    //'mapped' => false,

                    'required' => true,
                
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                "image/png",
                                "image/jpeg",
                                "image/jpg"
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ])
                    ],
                )
            )
            /*->add('files', CollectionType::class,array(
                    'attr' => ['class' => 'form-control'],
                    'entry_options' => [
                        'attr' => ['class' => 'form-control'],
                    ],
                    'entry_type' => FilesType::class,
                    'allow_add' => true,
                    'by_reference' => false,
                ))*/
            ->add('album', EntityType::class, array(
                    'class' => Album::class,
                    'choice_label' => 'name',
                    'required' => false
                )
            )
            ->add('description', TextType::class, array(
                    'attr'=>array('placeholder'=> 'Arthur pécho Jazzyyyy', 'class'=> 'form-control'),
                    'label' => 'Description',
                    'required' => false,
                )
            )
            
        ;
    }
    
    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Picture'
        ));
    }

}
