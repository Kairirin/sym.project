<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo',TextType::class,[
                'label' => 'Título:',
                'required' => true
                ])
            ->add('comentario',TextareaType::class,[
                'label' => 'Comentario:',
                'required' => true
                ])
            ->add('ruta_captura', FileType::class, [
                'label' => 'Captura videojuego (JPG o PNG)',
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
            /* ->add('juego', EntityType::class, [
                'class' => Juego::class,
                'choice_label' => 'id',
            ])
            ->add('autor', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('fecha',DateType::class, [
                'widget' => 'single_text']) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
