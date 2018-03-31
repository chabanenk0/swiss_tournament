<?php

namespace App\Services;

use App\Entity\Participant;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\RoundResult;
use App\Entity\Tournament;

class SwissPairingSystem implements PairingSystemInterface
{
    const CODE = 2;
    public function getCode()
    {
        return self::CODE;
    }

    public function getName()
    {
        return 'Swiss system';
    }

    public function doPairing(Tournament $tournament, Round $round, array $participants)
    {
        $this->writeJavafoInputFile();
        return $this->readJavafoOutputFile();
    }

    /**
     * @param Participant[] $participants
     * @return int
     */
    private function getRoutedPlayersNumber($participants)
    {
        $ratedPlayersNumber = 0;
        /** @var Participant $participant */
        foreach ($participants as $participant) {
            /** @var Player $player */
            $player = $participant->getPlayer();
            if ($player->getRating()) {
                $ratedPlayersNumber++;
            }
        }

        return $ratedPlayersNumber;
    }

    private function writeJavafoInputFile(Tournament $tournament, Round $round, $participants)
    {
        $fileName = $this->getTemporaryPath() . '/t_' . $tournament->getId(). '_r_' . $round. '_in.txt';
        $fileHandle = fopen($fileName, 'w');
        fprintf($fileHandle, "012 %s\n", $tournament->getTitle());
        fprintf($fileHandle, "022 %s\n", $tournament->getCity());
        fprintf($fileHandle, "032 %s\n", $tournament->getCountry());
        fprintf($fileHandle, "042 %s\n", (new \DateTime($tournament->getStartTimestamp()))->format('YYYY/mm/dd'));
        fprintf($fileHandle, "052 %s\n", (new \DateTime($tournament->getEndTimestamp()))->format('YYYY/mm/dd'));
        fprintf($fileHandle, "062 %d\n", count($participants));
        fprintf($fileHandle, "072 %d\n", $this->getRoutedPlayersNumber($participants));
        // fprintf($fileHandle, "082 %d\n", 0); number of teams.
        fprintf($fileHandle, "092 %s\n", 'Swiss System'); // @todo investigate possible allowed values "Individual: Swiss-System"
        fprintf($fileHandle, "102 %s\n", $tournament->getChiefArbiter());
        fprintf($fileHandle, "122 %s\n", $tournament->getAllotedTimePerMove());
        //fprintf($fileHandle, "132 %s\n", '');// // dates of all tours. Was absent (in their example) and empty (in mine, from swiss master)
        fprintf($fileHandle, "XXR %d\n", $tournament->getNumberOfRounds());
        //fprintf($fileHandle, "XXC black1\n"); // ???

        /** @var Participant $participant */
        foreach ($participants as $participant) {
            $this->writeParticipantInfo($fileHandle, $participant);
        }

        fclose($fileHandle);
    }

    /**
     * ? two characters, but examples have 3. Uppercase or lowercase?
     * In example there are lowercase 1 letter.
     * @todo will fix as soon as gms will begin using this system
     * @param Player $player
     * @return string
     */
    private function getPlayerTitle(Player $player)
    {
        switch ($player->getRange()) {
            case Player::RANGE_GM:
                $title = ' g';
                break;
            case Player::RANGE_MS:
                $title = ' m';
                break;
            default:
                $title = '  ';
        }

        return $title;
    }

    /**
     * @todo investigate possible results, update the model
     * The scheduled game was not played
    - forfeit loss
    + forfeit win
    The scheduled game lasted less than one move
    W win     Not rated
    D draw    Not rated
    L loss    Not rated
    Regular game
    1 win
    = draw
    0 loss
    Bye
    H half-point-bye    Not rated
    F full-point-bye    Not rated
    U pairing-allocated bye
    At most once for round - Not rated
    (U for player unpaired by the system)
    Z zero-point-bye
    Known absence from round - Not rated
    (blank) equivalent to Z
    Scheduled color against the scheduled opponent
    (minus) If the player had a bye or was not paired
    (blank) equivalent to -
    Note: Letter codes are case-insensitive (i.e. w,d,l,h,f,u,z can be used)
     *
     * @param $fileHandle
     * @param Participant $participant
     */
    private function writeParticipantInfo($fileHandle, Participant $participant)
    {
        $player = $participant->getPlayer();
        fprintf($fileHandle, "001 %4d ", $participant->getParticpantOrder());
        fprintf($fileHandle, "%c ", $player->getGender() === Player::GENDER_MALE ? 'm' : 'w');
        fprintf($fileHandle, "%2s", $this->getPlayerTitle($player));
        fprintf($fileHandle, "%32s ", $player->getLastName() . ' ' . $player->getFirstName());
        fprintf($fileHandle, "%4d ", $player->getFideRating());
        fprintf($fileHandle, "%3s ", $player->getFideFederation());
        fprintf($fileHandle, "%9s ", $player->getFideNumber() ? (string) $player->getFideNumber() : ' ');
        fprintf($fileHandle, "%4d ", (new \Date($player->getBirthDate()))->format('Y'));
        fprintf($fileHandle, "%2.1f ", $participant->getPoints());
        fprintf($fileHandle, "%4d ", $participant->getRank());
        fprintf($fileHandle, "\n");

        /** @var RoundResult $roundResult */
        foreach ($participant->getRoundResults() as $roundResult) {
            if ($roundResult->getResult() === RoundResult::RESULT_NO_PAIR) {
                fprintf($fileHandle, ' 0000 - Z ');
            }

            /** @var Participant $whiteParticipant */
            $whiteParticipant = $roundResult->getWhiteParticipant();
            /** @var Participant $blackParticipant */
            $blackParticipant = $roundResult->getBlackParticipant();

            if ($whiteParticipant->getId() === $participant->getId()) {
                fprintf(
                    $fileHandle,
                    " %4d w %1d ",
                    $blackParticipant->getParticpantOrder(),
                    (int) ($roundResult->getResult() === RoundResult::RESULT_BLACK_WIN)
                );
            } else {
                fprintf(
                    $fileHandle,
                    " %4d b %1d ",
                    $whiteParticipant->getParticpantOrder(),
                    (int) ($roundResult->getResult() === RoundResult::RESULT_WHITE_WIN)
                );
            }
        }
    }

    /**
     * @return string
     */
    private function getTemporaryPath()
    {
        // @todo get from app config
        return __DIR__ . implode(DS, array('var', 'cache', 'dev'));
    }
}
