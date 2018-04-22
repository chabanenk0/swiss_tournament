<?php

namespace App\Form\Type;

use App\Entity\RoundResult;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class RoundResultType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('result', ChoiceType::class, [
                'choices' => [
                    'білі виграли' => RoundResult::RESULT_WHITE_WIN,
                    'чорні виграли' => RoundResult::RESULT_BLACK_WIN,
                    'нічия' => RoundResult::RESULT_DRAW
                ],
                'placeholder' => $this->translator->trans('Select the winner'),
                'label' => $this->translator->trans('Round result')
            ])
            ->add('whiteParticipant', HiddenType::class)
            ->add('blackParticipant', HiddenType::class)
            ->add('tournament', HiddenType::class);
    }
}
