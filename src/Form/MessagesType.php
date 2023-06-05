<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add(
                'message',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )


            // ->add('file', FileType::class, [
            //     'label' => 'Ajouter un fichier PDF',
            //         'attr' => [
            //             'class' => 'form-control',
            //         ]
            // ])

            ->add('brochure', FileType::class, [
                'label' => 'Ficheier PDF à mettre si beosin',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('recipient', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'email',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            // ->add('created_at')
            // ->add('is_read')
            // ->add('sender', ) 
            // le sender c'est l'utilisateur connefcté 

            ->add('Envoyer', SubmitType::class, [

                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
