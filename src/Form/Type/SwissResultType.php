<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\RoundResult;

class SwissResultType extends AbstractType
{
    private $roundResult;

    public function __construct()
    {
        $this->roundResult = new RoundResult();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('result', ChoiceType::class, [
                'choices' => [
                    'Black win' => $this->roundResult::RESULT_BLACK_WIN,
                    'Draw' => $this->roundResult::RESULT_DRAW,
                    'White win' => $this->roundResult::RESULT_WHITE_WIN,
                ],
                'label' => 'Round result',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ])
        ->add('round', TextType::class);
    }
}

