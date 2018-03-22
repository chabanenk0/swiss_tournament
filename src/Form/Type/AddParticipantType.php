<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AddParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Players', ChoiceType::class, [
                'choices' => $options['data'],
                'attr' => [
                    'class' => 'state js-example-basic-single'
                ],
            ])
            ->add('Add', SubmitType::class, [
                'label' => 'Додати',
            ]);
    }

}

