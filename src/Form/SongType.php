<?php

namespace App\Form;

use App\Entity\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label' => 'Morceau (mp3 file)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/mp3',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid audio file',
                    ])
                    ]
                ))
                ->add('url',TextType::class, array(
                    'attr'=>array('placeholder'=> 'https://youtube.com/cjxkcsdhsd', 'class'=> 'form-control'),
                    'label' => 'Import d\'une vidéo Youtube',
                    'mapped' => false,
                    'required' => false,
                    )
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Song'
        ));
    }
}
