<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PairRoundResultType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('entry_type', RoundResultType::class);
        $resolver->setDefault('entry_options', ['label' => false]);
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}
