<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false, // No se asocia directamente con la entidad
                'required' => false, // Opcional, solo si quiere cambiarla
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repetir Password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Tu contraseña debe tener al menos 6 caracteres',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (JPG o PNG)',
                'data_class' => null,
                'constraints' => [
                    new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    ],
                    'mimeTypesMessage' => 'Por favor, seleccione un archivo jpg o png',
                ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
