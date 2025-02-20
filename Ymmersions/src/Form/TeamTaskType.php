<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\TeamTask;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateCreate', null, [
                'widget' => 'single_text',
            ])
            ->add('title')
            ->add('description')
            ->add('difficult')
            ->add('point')
            ->add('color')
            ->add('periodicity')
            ->add('target')
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamTask::class,
        ]);
    }
}
