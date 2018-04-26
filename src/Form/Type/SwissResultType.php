<?php

namespace App\Form\Type;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\ParticipantRepository;

class SwissResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('result', ChoiceType::class, [
                'choices' => [
                    'Black win' => 'RESULT_BLACK_WIN',
                    'Draw' => 'RESULT_DRAW',
                    'White win' => 'RESULT_WHITE_WIN',
                ],
                'label' => 'Round result',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ]);
    }
}
