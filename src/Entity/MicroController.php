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
     * @ORM\Column(type="float", nullable=true)
     */
    private $tempMax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tempMin;

    /**
     * @ORM\Column(type="float")
     */
    private $currentExtTemperature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $hours = [];

    /**
     * @ORM\OneToMany(targetEntity=TempHumidityRecord::class, mappedBy="microController", orphanRemoval=true)
     */
    private $tempHumidityRecords;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="microControllers")
     */
    private $users;

    /**
     * @ORM\Column(type="datetime")
     */
    private $apiLastCall;

    /**
     * @ORM\Column(type="float")
     */
    private $currentExtHumidity;

    public function __construct()
    {
        $this->tempHumidityRecords = new ArrayCollection();
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

    public function getTempMax(): ?float
    {
        return $this->tempMax;
    }

    public function setTempMax(?float $tempMax): self
    {
        $this->tempMax = $tempMax;

        return $this;
    }

    public function getTempMin(): ?float
    {
        return $this->tempMin;
    }

    public function setTempMin(?float $tempMin): self
    {
        $this->tempMin = $tempMin;

        return $this;
    }

    public function getcurrentExtTemperature(): ?float
    {
        return $this->currentExtTemperature;
    }

    public function setcurrentExtTemperature(float $currentExtTemperature): self
    {
        $this->currentExtTemperature = $currentExtTemperature;

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
     * @return Collection|TempHumidityRecord[]
     */
    public function getTempHumidityRecords(): Collection
    {
        return $this->tempHumidityRecords;
    }

    public function addTempHumidityRecord(TempHumidityRecord $tempHumidityRecord): self
    {
        if (!$this->tempHumidityRecords->contains($tempHumidityRecord)) {
            $this->tempHumidityRecords[] = $tempHumidityRecord;
            $tempHumidityRecord->setMicroController($this);
        }

        return $this;
    }

    public function removeTempHumidityRecord(TempHumidityRecord $tempHumidityRecord): self
    {
        if ($this->tempHumidityRecords->removeElement($tempHumidityRecord)) {
            // set the owning side to null (unless already changed)
            if ($tempHumidityRecord->getMicroController() === $this) {
                $tempHumidityRecord->setMicroController(null);
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

    public function getApiLastCall(): ?\DateTimeInterface
    {
        return $this->apiLastCall;
    }

    public function setApiLastCall(\DateTimeInterface $apiLastCall): self
    {
        $this->apiLastCall = $apiLastCall;

        return $this;
    }

    public function getCurrentExtHumidity(): ?float
    {
        return $this->currentExtHumidity;
    }

    public function setCurrentExtHumidity(float $currentExtHumidity): self
    {
        $this->currentExtHumidity = $currentExtHumidity;

        return $this;
    }
}
