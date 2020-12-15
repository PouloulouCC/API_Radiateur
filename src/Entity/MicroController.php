<?php

namespace App\Entity;

use App\Repository\MicroControllerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MicroControllerRepository::class)
 */
class MicroController
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $macAddress;

    /**
     * @ORM\Column(type="integer")
     */
    private $mode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tempMax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tempMin;

    /**
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $hours = [];

    /**
     * @ORM\OneToMany(targetEntity=TemperatureRecord::class, mappedBy="microController", orphanRemoval=true)
     */
    private $temperatureRecords;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="microControllers")
     */
    private $users;

    public function __construct()
    {
        $this->temperatureRecords = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMacAddress(): ?string
    {
        return $this->macAddress;
    }

    public function setMacAddress(string $macAddress): self
    {
        $this->macAddress = $macAddress;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getTempMax(): ?int
    {
        return $this->tempMax;
    }

    public function setTempMax(int $tempMax): self
    {
        $this->tempMax = $tempMax;

        return $this;
    }

    public function getTempMin(): ?int
    {
        return $this->tempMin;
    }

    public function setTempMin(int $tempMin): self
    {
        $this->tempMin = $tempMin;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getHours(): ?array
    {
        return $this->hours;
    }

    public function setHours(?array $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * @return Collection|TemperatureRecord[]
     */
    public function getTemperatureRecords(): Collection
    {
        return $this->temperatureRecords;
    }

    public function addTemperatureRecord(TemperatureRecord $temperatureRecord): self
    {
        if (!$this->temperatureRecords->contains($temperatureRecord)) {
            $this->temperatureRecords[] = $temperatureRecord;
            $temperatureRecord->setMicroController($this);
        }

        return $this;
    }

    public function removeTemperatureRecord(TemperatureRecord $temperatureRecord): self
    {
        if ($this->temperatureRecords->removeElement($temperatureRecord)) {
            // set the owning side to null (unless already changed)
            if ($temperatureRecord->getMicroController() === $this) {
                $temperatureRecord->setMicroController(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
