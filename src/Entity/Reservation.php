<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
    private $total = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $passengers;

    /**
     * @ORM\Column(type="integer")
     */
    private $vehicles;

    /**
     * One Reservation has Many Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="reservation")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customers;

    /**
     * One Ferry has Many Reservation
     * @ORM\ManyToOne(targetEntity="Ferry", inversedBy="reservations")
     * @ORM\JoinColumn(name="ferry_id", referencedColumnName="id")
     */
    protected $ferry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

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

    /**
     * @return Ferry
     */
    public function getFerry() : Ferry
    {
        return $this->ferry;
    }

    /**
     * @param Ferry $ferry
     *
     * @return $this
     */
    public function setFerry(Ferry $ferry): self
    {
        $this->ferry = $ferry;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCustomers() : ?ArrayCollection
    {
        return $this->customers;
    }

    /**
     * @param Customer $customers
     *
     * @return $this
     */
    public function setCustomers(Customer $customers): self
    {
        $this->customers = $customers;

        return $this;
    }

    public function calculateTotal() {

        $total = 0;
        $total = $this->getVehicles() * $this->getFerry()->getPricePerVehicle();
        $total += $this->getPassengers() * $this->getFerry()->getPricePerPassenger();


        $this->setTotal($total);

        return $total;
    }


}
