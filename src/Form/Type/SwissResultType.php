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
            ->add('participant', ChoiceType::class, [
                'choices' => [
                    'opt' => $options,
                ]
            ])
            ->add('result', ChoiceType::class, [
                'choices' => [
                    'mid' => 0,
                    'win' => 1,
                    'fale' => 2,
                ]
            ])
            ->add('remove', SubmitType::class, [
                'label' => 'Видалити',
            ]);
    }
}
