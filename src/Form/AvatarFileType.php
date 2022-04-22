<?php

namespace App\Form;

use App\Entity\AvatarFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AvatarFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
        ->add('imageFile',
            VichImageType::class, [
                'label' => " ",
                'required' => false,
                'download_uri' => false,
                'allow_delete' => false,
                'image_uri' => true
            ])
    ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvatarFile::class,
        ]);
    }
}
