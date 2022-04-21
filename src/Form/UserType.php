<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatarfiles',
                AvatarFileType::class)
            ->add('pseudo', TextType::class,
                ["label" => 'Pseudo'])
            ->add('firstName', TextType::class,
                ["label" => 'Prénom'])
            ->add('lastName', TextType::class,
                ["label" => 'Nom'])
            ->add('phone', TextType::class,
                ["label" => 'Téléphone'])

            ->add('email', TextType::class,
                ["label" => 'Email'])

            ->add('password', PasswordType::class,
            [
                "label" => 'Saisir le mot de passe pour confirmer',
                'invalid_message' => 'Mot de passe incorrect',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir le mot de passe pour valider la modification du profil',
                    ]),
            ]])
           ;

//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'Les mots de passe doivent correspondre',
//                'options' => ['attr' => ['class' => 'password-field']],
//                'required' => true,
//                'first_options' => ['label' => 'Mot de passe'],
//                'second_options' => ['label' => 'Saisir à nouveau le mot de passe'],
//                'mapped' => false,
//                'attr' => ['autocomplete' => 'new-password'],
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Please enter a password',
//                    ]),
//                    new Length([
//                        'min' => 6,
//                        'minMessage' => 'Your password should be at least {{ limit }} characters',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 4096,
//                    ]),
//                ]
//            ])

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
