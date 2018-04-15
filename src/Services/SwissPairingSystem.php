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

    private $roundResultsByParticipants = array();

    /**
     * @return int
     */
    public function getCode()
    {
        return self::CODE;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Swiss system';
    }

    /**
     * @param RoundResult[] $roundResults
     */
    private function arrangeRoundResults(array $roundResults)
    {
        /** @var RoundResult $roundResult */
        foreach ($roundResults as $roundResult) {
            /** @var Participant $blackParticipant */
            $blackParticipant = $roundResult->getBlackParticipant();
            $blackParticipantOrderNumber = $blackParticipant->getParticpantOrder();
            /** @var Participant $whiteParticipant */
            $whiteParticipant = $roundResult->getBlackParticipant();
            $whiteParticipantOrderNumber = $whiteParticipant->getParticpantOrder();
            /** @var Round $round */
            $round = $roundResult->getRound();
            $roundNumber = $round->getNumber();

            if (!array_key_exists($blackParticipantOrderNumber, $this->roundResultsByParticipants)) {
                $this->roundResultsByParticipants[$blackParticipantOrderNumber] = [];
            }

            if (!array_key_exists($roundNumber, $this->roundResultsByParticipants[$blackParticipantOrderNumber])) {
                $this->roundResultsByParticipants[$blackParticipantOrderNumber][$roundNumber] = [];
            }

            $this->roundResultsByParticipants[$blackParticipantOrderNumber][$roundNumber][] = $roundResult;

            if (!array_key_exists($whiteParticipantOrderNumber, $this->roundResultsByParticipants)) {
                $this->roundResultsByParticipants[$whiteParticipantOrderNumber] = [];
            }

            if (!array_key_exists($roundNumber, $this->roundResultsByParticipants[$whiteParticipantOrderNumber])) {
                $this->roundResultsByParticipants[$whiteParticipantOrderNumber][$roundNumber] = [];
            }

            $this->roundResultsByParticipants[$whiteParticipantOrderNumber][$roundNumber][] = $roundResult;
        }
    }

    /**
     * @param Tournament $tournament
     * @param Round $round
     * @param array $participants
     * @param array $roundResults
     * @return RoundResult[]
     * @throws \Exception
     */
    public function doPairing(Tournament $tournament, Round $round, array $participants, array $roundResults)
    {
        $this->arrangeRoundResults($roundResults);
        $inFileName = $this->writeJavafoInputFile($tournament, $round, $participants);
        $outFileName = $this->runJavafo($inFileName);
        $pairsNumbersArray = $this->readJavafoOutputFile($outFileName);

        return $this->generateNewRoundPairs($tournament, $round, $participants, $pairsNumbersArray);
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
            if ($player->getFideRating()) {
                $ratedPlayersNumber++;
            }
        }

        return $ratedPlayersNumber;
    }

    private function writeJavafoInputFile(Tournament $tournament, Round $round, $participants)
    {
        $fileName = $this->getTemporaryPath() . '/t_' . $tournament->getId(). '_r_' . $round->getNumber() . '_in.txt';
        $fileHandle = fopen($fileName, 'w');
        fprintf($fileHandle, "012 %s\n", $tournament->getTitle());
        fprintf($fileHandle, "022 %s\n", $tournament->getCity());
        fprintf($fileHandle, "032 %s\n", $tournament->getCountry());
        $startTime = new \DateTime();
        $startTime->setTimestamp($tournament->getStartTimestamp());
        fprintf($fileHandle, "042 %s\n", $startTime->format('Y/m/d'));
        $endTime = new \DateTime();
        $endTime->setTimestamp($tournament->getEndTimestamp());
        fprintf($fileHandle, "052 %s\n", $endTime->format('Y/m/d'));
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
            if (!$participant->getRank()) {
                $participant->setRank($participant->getParticpantOrder());
            }

            $this->writeParticipantInfo($fileHandle, $participant);
        }

        fclose($fileHandle);

        return $fileName;
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
        fprintf($fileHandle, "%s ", $player->getGender() === Player::GENDER_MALE ? 'm' : 'w');
        fprintf($fileHandle, "%2s  ", $this->getPlayerTitle($player));
        fprintf($fileHandle, "%32s ", $player->getLastName() . ' ' . $player->getFirstName());
        fprintf($fileHandle, "%4d ", $player->getFideRating());
        fprintf($fileHandle, "%3s ", $player->getFideFederation());
        fprintf($fileHandle, "%11s ", $player->getFideNumber() ? (string) $player->getFideNumber() : ' ');
        fprintf($fileHandle, "%10s  ", $player->getBirthDate()->format('Y/m/d'));
        fprintf($fileHandle, "%2.1f ", $participant->getPoints());
        fprintf($fileHandle, "%4d ", $participant->getRank());

        $roundResults = array_key_exists($participant->getParticpantOrder(), $this->roundResultsByParticipants)
            ? $this->roundResultsByParticipants[$participant->getParticpantOrder()]
            : [];

        /** @var RoundResult $roundResult */
        foreach ($roundResults as $roundResultByRound) {
            break; // @todo debug when rounds are present
            foreach ($roundResultByRound as $roundResult) {
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

        fprintf($fileHandle, "           \n");
    }

    /**
     * @return string
     */
    private function getTemporaryPath()
    {
        // @todo get from app config
        return __DIR__ . '/' . implode('/', ['..', '..', 'var', 'cache', 'dev']);
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function runJavafo($fileName)
    {
        $outFileName = str_replace('_in.txt', '_out.txt', $fileName);
        $javafoExecutablePath = $this->getVendorPath() . '/' . implode('/', ['javafo', 'javafo']) . '/javafo.jar';

        $command = 'java -ea -jar ' . $javafoExecutablePath . ' ' .$fileName . ' -p ' . $outFileName;

        $result = exec($command);

        return $outFileName;
    }

    /**
     * @param string $outFileName
     * @return array
     */
    public function readJavafoOutputFile($outFileName)
    {
        $fileHandle = fopen($outFileName, 'r');
        $pairsNumber = fscanf($fileHandle, "%d\n");

        $pairsNumber = array_pop($pairsNumber);
        $pairsArray = [];

        for ($i = 0; $i < $pairsNumber; $i++) {
            $data = fscanf($fileHandle, "%d %d\n");
            $pairsArray [] = [
                'white_number' => (int) $data[0],
                'black_number' => (int) $data[1]
            ];
        }

        return $pairsArray;
    }

    /**
     * @param Tournament $tournament
     * @param Round $round
     * @param array $participants
     * @param $pairsNumbersArray
     * @return RoundResult[]
     * @throws \Exception
     */
    public function generateNewRoundPairs(Tournament $tournament, Round $round, array $participants, $pairsNumbersArray)
    {
        $participantsByNumber = [];

        /** @var Participant $participant */
        foreach ($participants as $participant) {
            $participantsByNumber[$participant->getParticpantOrder()] = $participant;
        }

        $newRoundResults = [];

        foreach ($pairsNumbersArray as $pairData) {
            $roundResult = new RoundResult();
            $roundResult->setTournament($tournament);
            $roundResult->setRound($round);
            /** @var Participant|null $whiteParticipant */
            $whiteParticipant = null;

            if ($pairData['white_number'] !== 0) {
                $whiteParticipant = $this->getParticipantByNumber($pairData['white_number'], $participantsByNumber);
                $roundResult->setWhiteParticipant($whiteParticipant);
            }

            /** @var Participant|null $blackParticipant */
            $blackParticipant = null;

            if ($pairData['black_number'] !== 0) {
                $blackParticipant = $this->getParticipantByNumber($pairData['black_number'], $participantsByNumber);
                $roundResult->setBlackParticipant($blackParticipant);
            }

            if (!$blackParticipant || !$whiteParticipant) {
                $roundResult->setResult(RoundResult::RESULT_NO_PAIR);
            }

            $newRoundResults [] = $roundResult;
        }

        return $newRoundResults;
    }

    /**
     * @param $number
     * @param array $participants
     * @return Participant
     * @throws \Exception
     */
    private function getParticipantByNumber($number, array $participants)
    {
        if (!array_key_exists($number, $participants)) {
            throw new \Exception('Wrong participant number - ' . $number);
        }

        return $participants[$number];
    }

    /**
     * @return string
     */
    private function getVendorPath()
    {
        // @todo get from app config
        return __DIR__ . '/' . implode('/', ['..', '..', 'vendor']);
    }
}
