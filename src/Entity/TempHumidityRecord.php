<?php

namespace App\Entity;

use App\Repository\TempHumidityRecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TempHumidityRecordRepository::class)
 */
class TempHumidityRecord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MicroController::class, inversedBy="tempHumidityRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $microController;

    /**
     * @ORM\Column(type="float")
     */
    private $temperatureInt;

    /**
     * @ORM\Column(type="float")
     */
    private $temperatureExt;

    /**
     * @ORM\Column(type="float")
     */
    private $HumidityInt;

    /**
     * @ORM\Column(type="float")
     */
    private $HumidityExt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMicroController(): ?MicroController
    {
        return $this->microController;
    }

    public function setMicroController(?MicroController $microController): self
    {
        $this->microController = $microController;

        return $this;
    }

    public function getTemperatureInt(): ?float
    {
        return $this->temperatureInt;
    }

    public function setTemperatureInt(float $temperatureInt): self
    {
        $this->temperatureInt = $temperatureInt;

        return $this;
    }

    public function getTemperatureExt(): ?float
    {
        return $this->temperatureExt;
    }

    public function setTemperatureExt(float $temperatureExt): self
    {
        $this->temperatureExt = $temperatureExt;

        return $this;
    }

    public function getHumidityInt(): ?int
    {
        return $this->HumidityInt;
    }

    public function setHumidityInt(int $HumidityInt): self
    {
        $this->HumidityInt = $HumidityInt;

        return $this;
    }

    public function getHumidityExt(): ?float
    {
        return $this->HumidityExt;
    }

    public function setHumidityExt(float $HumidityExt): self
    {
        $this->HumidityExt = $HumidityExt;

        return $this;
    }
}
