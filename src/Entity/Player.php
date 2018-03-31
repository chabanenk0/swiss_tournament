<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @ORM\Column(type="string", length=100, name="first_name")
     */
    private $firstName = '';

    /**
     * @ORM\Column(type="string", length=100, name="fathers_name", nullable=true)
     */
    private $fathersName = '';

    /**
     * @ORM\Column(type="string", length=100, name="last_name")
     */
    private $lastName = '';

    /**
     * @ORM\Column(type="string", length=255, name="avatar_src", nullable=true)
     */
    private $avatarSrc = '';

    /** Gender constants */
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @ORM\Column(type="integer", name="gender")
     */
    private $gender = 0;

    /**
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="player")
     */
    private $participants;

    /**
     * @ORM\Column(type="date", name="birth_date", nullable=true)
     */
    private $birthDate = '';

    /**
     * Range (titul) constants
     * @todo add the following values: GM, IM, WGM, FM, WIM, CM, WFM, WCM
     * use 'title' property value
     */
    const RANGE_1_ROZRYAD = 1;
    const RANGE_2_ROZRYAD = 2;
    const RANGE_3_ROZRYAD = 3;
    const RANGE_KMS = 4;
    const RANGE_MS = 5;
    const RANGE_GM = 6;

    /**
     * @ORM\Column(type="integer", name="rang", nullable=true)
     */
    private $range = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $federation = '';

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $phone = '';
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $email = '';

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return (string) $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFathersName(): string
    {
        return (string) $this->fathersName;
    }

    /**
     * @param string $fathersName
     */
    public function setFathersName(string $fathersName): void
    {
        $this->fathersName = $fathersName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return (string) $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getAvatarSrc(): string
    {
        return (string) $this->avatarSrc;
    }

    /**
     * @param string $avatarSrc
     */
    public function setAvatarSrc($avatarSrc): void
    {
        $this->avatarSrc = $avatarSrc;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return (int) $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getBirthDate(): \DateTime
    {
        return $this->birthDate ?: new \DateTime();
    }

    /**
     * @param string $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return int
     */
    public function getRange(): int
    {
        return (int) $this->range;
    }

    /**
     * @param int $range
     */
    public function setRange($range): void
    {
        $this->range = $range;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return (string) $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getFederation(): string
    {
        return (string) $this->federation;
    }

    /**
     * @param mixed $federation
     */
    public function setFederation($federation): void
    {
        $this->federation = $federation;
    }

    /**
     * @return mixed
     */
    public function getPhone(): string
    {
        return (string) $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @param Participant $participant
     */
    public function addParticipant(Participant $participant)
    {
        $this->participants->add($participant);
    }

    /**
     * @param Participant $participant
     */
    public function removeParticipant(Participant $participant)
    {
        $this->participants->removeElement($participant);
    }

    public function getFideRating()
    {
        return 0;
    }

    public function getFideFederation()
    {
        return '   ';
    }

    public function getFideNumber()
    {
        return 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->lastName . ' ' . $this->firstName;
    }
}
