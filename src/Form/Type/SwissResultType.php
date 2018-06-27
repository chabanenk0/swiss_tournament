<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\RoundResult;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class SwissResultType extends AbstractType
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
            ->add('id', HiddenType::class)
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
                    $this->translator->trans('White win') => RoundResult::RESULT_WHITE_WIN,
                    $this->translator->trans('Black win') => RoundResult::RESULT_BLACK_WIN,
                    $this->translator->trans('Draw') => RoundResult::RESULT_DRAW
                ],
                'placeholder' => $this->translator->trans('Select the winner'),
                'label' => $this->translator->trans('Round result')
            ])
            ->add('Add', SubmitType::class, [
                'label' => 'Save',
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoundResult::class
        ]);
    }
}
