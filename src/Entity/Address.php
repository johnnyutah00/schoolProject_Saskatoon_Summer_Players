<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
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
    private $houseApartNum;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $streetName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $postalCode;

    /**
     * Address constructor.
     * @param $id
     * @param $houseApartNum
     * @param $streetName
     * @param $city
     * @param $province
     * @param $postalCode
     */
    public function __construct($id, $houseApartNum, $streetName, $city, $province, $postalCode)
    {
        $this->id = $id;
        $this->houseApartNum = $houseApartNum;
        $this->streetName = $streetName;
        $this->city = $city;
        $this->province = $province;
        $this->postalCode = $postalCode;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHouseApartNum(): ?int
    {
        return $this->houseApartNum;
    }

    public function setHouseApartNum(int $houseApartNum): self
    {
        if(!is_int($houseApartNum))
        {
            throw new \InvalidArgumentException("The House/Apartment Number Attribute has to be an integer.");
        }
        $this->houseApartNum = $houseApartNum;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        //if name is >50 characters or is numeric throw exception
        if(strlen($streetName) > 50 || is_int($streetName))//
        {
            throw new \InvalidArgumentException("The StreetName has to be a string below 50 characters");
        }
        $this->streetName = $streetName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        //if name is >50 characters or is numeric throw exception
        if(strlen($city) > 50 || is_int($city))
        {
            throw new \InvalidArgumentException("The City has to be a string below 50 characters");
        }
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        //if name is >50 characters or is numeric throw exception
        if(strlen($province) > 50 || is_int($province))
        {
            throw new \InvalidArgumentException("The Province Attribute has to be a string and below 50 characters.");
        }
        $this->province = $province;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        //if name is >50 characters or is numeric throw exception
        if(strlen($postalCode) > 6 || is_int($postalCode))
        {
            throw new \InvalidArgumentException("The Postal code has to be a string and has to be below 6 characters");
        }
        $this->postalCode = $postalCode;

        return $this;
    }

    public function __toString()
    {
        return $this->houseApartNum . ' ' . $this->streetName . ' - ' . $this->getCity() . ', ' . $this->getProvince() . ' ' . $this->getPostalCode();
    }
}
