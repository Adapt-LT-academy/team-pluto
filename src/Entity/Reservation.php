<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $passengers;

    /**
     * @ORM\Column(type="integer")
     */
    private $vehicles;

     /**
     * One Reservation has One Customer.
     * @ORM\OneToOne(targetEntity="Customer", mappedBy="reservation", cascade={"persist"})
     */
    protected $customer_id;

     /**
     * One Reservation has One Ferries.
     * @ORM\OneToOne(targetEntity="Ferries", mappedBy="reservation", cascade={"persist"})
     */
    protected $ferry_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPassengers(): ?int
    {
        return $this->passengers;
    }

    public function setPassengers(int $passengers): self
    {
        $this->passengers = $passengers;

        return $this;
    }

    public function getVehicles(): ?int
    {
        return $this->vehicles;
    }

    public function setVehicles(int $vehicles): self
    {
        $this->vehicles = $vehicles;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
      return $this->customer_id;
    }

    /**
     * @param Customer $customer
     *
     * @return $this
     */
    public function addCustomer(Customer $customer): self
    {
      $this->customer_id = $customer;

      return $this;
    }

    public function getFerries(): ?Ferries
    {
      return $this->ferry_id;
    }

    /**
     * @param Ferries $ferries
     *
     * @return $this
     */
    public function addFerries(Ferries $ferries): self
    {
      $this->ferry_id = $ferries;

      return $this;
    }

}
