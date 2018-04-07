<?php

namespace App\Services;

use App\Entity\Tournament;
use App\Exceptions\UndefinedPairSystemCode;

class PairingSystemProvider
{
    private $pairingSystemConfigurations = [];

    public function __construct()
    {
    }

    /**
     * @param Tournament $tournament
     * @return PairingSystemInterface
     * @throws UndefinedPairSystemCode
     */
    public function getPairingSystemByTournament(Tournament $tournament)
    {
        return $this->getPairingSystemByCode($tournament->getPairingSystem());
    }

    /**
     * @param int $code
     * @return mixed
     * @throws UndefinedPairSystemCode
     */
    public function getPairingSystemByCode(int $code)
    {
        if (!array_key_exists($code, $this->pairingSystemConfigurations)) {
            throw new UndefinedPairSystemCode('Undefined pair system code:' . $code);
        }

        return $this->pairingSystemConfigurations[$code]['service'];
    }

    public function getPairingSystemsNamesAndCodes()
    {
        $pairingSystemsDataArray = [];

        foreach ($this->pairingSystemConfigurations as $code => $pairingSystemConfiguration) {
            $pairingSystemsDataArray[$code] = $pairingSystemConfiguration['name'];
        }

        return $pairingSystemsDataArray;
    }

    public function addPairingSystem(PairingSystemInterface $pairingSystem)
    {
        $code = $pairingSystem->getCode();
        $name = $pairingSystem->getName();
        $this->pairingSystemConfigurations[$code] = [
            'name' => $name,
            'service' => $pairingSystem,
        ];
    }
}
