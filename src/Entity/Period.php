<?php

namespace App\Entity;

use App\Repository\PeriodRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=PeriodRepository::class)
 */
class Period
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $weekDay;

    /**
     * @ORM\Column(type="time")
     */
    private $timeStart;

    /**
     * @ORM\Column(type="time")
     */
    private $timeEnd;

    /**
     * @ORM\ManyToOne(targetEntity=MicroController::class, inversedBy="periods")
     * @ORM\JoinColumn(nullable=false)
     * @Ignore()
     */
    private $microController;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekDay(): ?int
    {
        return $this->weekDay;
    }

    public function setWeekDay(int $weekDay): self
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    public function getTimeStart(): ?DateTimeInterface
    {
        return $this->timeStart;
    }

    public function setTimeStart(DateTimeInterface $timeStart): self
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    public function getTimeEnd(): ?DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
