<?php

namespace App\Form\Type;

use App\Entity\RoundResult;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('id', HiddenType::class, [
                'mapped' => false
            ])
            ->add('whiteParticipantLastName', TextType::class, [
                'label' => $this->translator->trans('White participant last name'),
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('blackParticipantLastName', TextType::class, [
                'label' => $this->translator->trans('Black participant last name'),
                'attr' => [
        'readonly' => true
    ]
            ])
            ->add('result', ChoiceType::class, [
        'choices' => [
            'білі виграли' => RoundResult::RESULT_WHITE_WIN,
            'чорні виграли' => RoundResult::RESULT_BLACK_WIN,
            'нічия' => RoundResult::RESULT_DRAW
        ],
        'placeholder' => $this->translator->trans('Select the winner'),
        'label' => $this->translator->trans('Round result')
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoundResult::class
        ]);
    }
}
