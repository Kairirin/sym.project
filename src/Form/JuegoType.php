<?php

namespace App\Form;

use App\Entity\Juego;
use App\Entity\Plataforma;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class JuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre',TextType::class,[
                'label' => 'Nombre:',
                'required' => true
                ])
            ->add('imagen', FileType::class, [
                'label' => 'Portada videojuego (JPG o PNG)',
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
            ->add('plataforma', EntityType::class, [
                'class' => Plataforma::class,
                'choice_label' => 'nombre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}
