<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                [
                    'label'=>'Votre prénom',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label'=>'Votre nom',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )

            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Votre email', 
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', PasswordType::class, [
                'label'=>'Votre mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])


            ->add(
                'cp',
                IntegerType::class,
                [
                    'label'=>'Code postal',
                    'attr' => [
                        'class' => 'form-control',
                        'label' => 'Code postal'
                    ]
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'label' => 'Votre ville',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add(
                'userprofile',
                TextType::class,
                [
                    'label' => 'Photo de profile ou lien ',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mt-2 container bold',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
