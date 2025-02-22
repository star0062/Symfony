<?php

namespace App\Form;

use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Build the form fields here
        $builder
            ->add('name', TextType::class, [
                'label' => 'Group Name',
            ])
            ->add('score', IntegerType::class, [
                'label' => 'Score',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Group',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Set the form to use the Group entity
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
