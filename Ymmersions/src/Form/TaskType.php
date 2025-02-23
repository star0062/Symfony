<?php

namespace App\Form;

use App\Entity\TeamTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('difficult', ChoiceType::class, [
                'label' => 'Difficulté',
                'choices' => [
                    'Très facile' => 1,
                    'Facile' => 2,
                    'Moyenne' => 5,
                    'Difficile' => 10,
                ],
            ])
            ->add('color', ColorType::class, ['label' => 'Couleur'])
            ->add('periodicity', ChoiceType::class, [
                'label' => 'Périodicité',
                'choices' => [
                    'Quotidienne' => 'daily',
                    'Hebdomadaire' => 'weekly',
                ],
            ])
            ->add('target', ChoiceType::class, [
                'label' => 'Cible',
                'choices' => [
                    'Individuelle' => 'individual',
                    'Équipe' => 'team',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TeamTask::class,
        ]);
    }
}
