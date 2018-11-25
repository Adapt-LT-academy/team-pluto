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
    private $starting_doc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destination_doc;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_passengers;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_vehicles;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_per_passenger;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_per_vehicle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * One Ferry has Many Reservation
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="ferry")
     */
    private $reservations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDoc(): ?string
    {
        return $this->starting_doc;
    }

    public function setStartingDoc(string $starting_doc): self
    {
        $this->starting_doc = $starting_doc;

        return $this;
    }

    public function getDestinationDoc(): ?string
    {
        return $this->destination_doc;
    }

    public function setDestinationDoc(string $destination_doc): self
    {
        $this->destination_doc = $destination_doc;

        return $this;
    }

    public function getMaxPassengers(): ?int
    {
        return $this->max_passengers;
    }

    public function setMaxPassengers(int $max_passengers): self
    {
        $this->max_passengers = $max_passengers;

        return $this;
    }

    public function getMaxVehicles(): ?int
    {
        return $this->max_vehicles;
    }

    public function setMaxVehicles(int $max_vehicles): self
    {
        $this->max_vehicles = $max_vehicles;

        return $this;
    }

    public function getPricePerPassenger(): ?int
    {
        return $this->price_per_passenger;
    }

    public function setPricePerPassenger(int $price_per_passenger): self
    {
        $this->price_per_passenger = $price_per_passenger;

        return $this;
    }

    public function getPricePerVehicle(): ?int
    {
        return $this->price_per_vehicle;
    }

    public function setPricePerVehicle(int $price_per_vehicle): self
    {
        $this->price_per_vehicle = $price_per_vehicle;

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
