<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FerryRepository")
 */
class Ferry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $startingDoc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destinationDoc;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPassengers;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxVehicles;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricePerPassenger;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricePerVehicle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * One Ferry has Many Reservation
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="ferry")
     */
    private $reservations;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStartingDoc(): string
    {
        return $this->startingDoc;
    }

    /**
     * @param string $startingDoc
     *
     * @return $this
     */
    public function setStartingDoc(string $startingDoc): self
    {
        $this->startingDoc = $startingDoc;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationDoc(): string
    {
        return $this->destinationDoc;
    }

    /**
     * @param string $destinationDoc
     *
     * @return $this
     */
    public function setDestinationDoc(string $destinationDoc): self
    {
        $this->destinationDoc = $destinationDoc;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPassengers(): int
    {
        return $this->maxPassengers;
    }

    /**
     * @param int $maxPassengers
     *
     * @return $this
     */
    public function setMaxPassengers(int $maxPassengers): self
    {
        $this->maxPassengers = $maxPassengers;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxVehicles(): int
    {
        return $this->maxVehicles;
    }

    /**
     * @param int $maxVehicles
     *
     * @return $this
     */
    public function setMaxVehicles(int $maxVehicles): self
    {
        $this->maxVehicles = $maxVehicles;

        return $this;
    }

    /**
     * @return int
     */
    public function getPricePerPassenger(): int
    {
        return $this->pricePerPassenger;
    }

    /**
     * @param int $pricePerPassenger
     *
     * @return $this
     */
    public function setPricePerPassenger(int $pricePerPassenger): self
    {
        $this->pricePerPassenger = $pricePerPassenger;

        return $this;
    }

    /**
     * @return int
     */
    public function getPricePerVehicle(): int
    {
        return $this->pricePerVehicle;
    }

    /**
     * @param int $pricePerVehicle
     *
     * @return $this
     */
    public function setPricePerVehicle(int $pricePerVehicle): self
    {
        $this->pricePerVehicle = $pricePerVehicle;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReservations() : ?ArrayCollection
    {
        return $this->reservations;
    }

    /**
     * @param Reservation $reservation
     *
     * @return $this
     */
    public function setReservation(Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }
}
