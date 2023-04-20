<?php

namespace App\Form;

use App\Entity\CharacterChoice;

use App\Entity\Serie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('name', TextType::class)
            ->add('weight', TextType::class)
            ->add('speed', TextType::class)
            ->add('tier', ChoiceType::class, [
                'choices' => [
                    'S+' => 'S+',
                    'S' => 'S',
                    'S-' => 'S-',
                    'A+' => 'A+',
                    'A' => 'A',
                    'A-' => 'A-',
                    'B+' => 'B+',
                    'B' => 'B',
                    'B-' => 'B-',
                    'C+' => 'C+',
                    'C' => 'C',
                    'C-' => 'C-',
                    'D+' => 'D+',
                    'D' => 'D',
                    'D-' => 'D-',
                ]
            ])
            ->add('iterationNumber', TextType::class)
            ->add('imagePath', FileType::class, [
                'label' => "Image",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '11000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Please upload a valid image",
                    ])
                ],
            ])
            ->add('serie', EntityType::class, [
                'class' => Serie::class,
                'query_builder' =>  function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', "ASC");
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterChoice::class,
        ]);
    }
}
