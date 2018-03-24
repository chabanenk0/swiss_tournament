<?php

namespace App\Form\Type;

use App\Entity\Participant;
use App\Entity\Player;
use App\Repository\ParticipantRepository;
use App\Repository\PlayerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AddParticipantType extends AbstractType
{
    private $participantRepository;

    public function __construct(ParticipantRepository $repository)
    {
        $this->participantRepository = $repository;
    }

    private function getUsedPlayerIdsByTournamentId($torunamentId)
    {
        $participants = $this->participantRepository->findBy(['tournament' => $torunamentId]);
        return array_map(function(Participant $participant) {
            return $participant->getPlayer()->getId();
        }, $participants);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $addedPlayerIds = [];
        /** @var Participant $participant */
        if (isset($options['data']) && $participant = $options['data']) {
            $tournament = $participant->getTournament();
            $addedPlayerIds = $this->getUsedPlayerIdsByTournamentId($tournament->getId());
        }

        $builder
            ->add('player', EntityType::class, array(
                'class' => Player::class,
                'query_builder' => function (PlayerRepository $repository) use ($addedPlayerIds) {
                    $queryBuilder = $repository->createQueryBuilder('u');

                    $queryBuilder->orderBy('u.lastName', 'ASC');
                    if ($addedPlayerIds) {
                        $queryBuilder->where($queryBuilder->expr()->notIn('u.id', $addedPlayerIds));
                    }

                    return $queryBuilder;
                },
                'attr' => [
                    'class' => 'state js-example-basic-single'
                ]
            ))
            ->add('Add', SubmitType::class, [
                'label' => 'Додати',
            ]);
    }
}
