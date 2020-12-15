<?php

namespace App\Entity;

use App\Repository\TemperatureRecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemperatureRecordRepository::class)
 */
class TemperatureRecord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MicroController::class, inversedBy="temperatureRecords")
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
}
