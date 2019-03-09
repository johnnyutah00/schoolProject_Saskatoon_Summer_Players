<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShowRepository")
 * @Vich\Uploadable
 */
class SSPShow
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid number."
     * )
     * @ORM\Column(type="float", nullable=true)
     */
    private $ticketPrice;

    /**
     * @Assert\Length(
     *      min = 0,
     *      max = 100,
     *      minMessage = "The picture path must be at least {{ limit }} characters long",
     *      maxMessage = "The picture path cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $picturePath;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="SSPShow_picture", fileNameProperty="picturePath", size="pictureSize")
     * @Assert\File(
     *     maxSize = "3072k",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid image",
     *     maxSizeMessage = "The picture cannot be larger than 3MB."
     * )
     * @var File
     */
    private $pictureFile;


    /**
     *
     *
     * @var integer
     */
    private $pictureSize;

    /**
     *
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The URL cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ticketLink;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="text")
     */
    private $synopsis;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid number."
     * )
     * @ORM\Column(type="float", nullable=true)
     */
    private $budget;

    /**
     * SSPShow constructor.
     * @param $id
     * @param $name
     * @param $date
     * @param $ticketPrice
     * @param $picturePath
     * @param $ticketLink
     * @param $synopsis
     * @param $location
     * @param $endDate
     * @param $status
     * @param $budget
     */
    public function __construct($id, $name, $date, $ticketPrice, $location, $synopsis, $picturePath, $ticketLink, $endDate=null, $status='', $budget=0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->endDate = $endDate;
        $this->ticketPrice = $ticketPrice;
        $this->picturePath = $picturePath;
        $this->ticketLink = $ticketLink;
        $this->synopsis = $synopsis;
        $this->location = $location;
        $this->status = $status;
        $this->budget = $budget;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getTicketPrice(): ?float
    {
        return $this->ticketPrice;
    }

    public function setTicketPrice(?float $ticketPrice): self
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(?string $picturePath): self
    {
        $this->updatedAt = new DateTime();
        $this->picturePath = $picturePath;

        return $this;
    }


    public function setPictureFile(?File $pictureFile = null): void
    {
        $this->pictureFile = $pictureFile;

        if (null !== $pictureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime();
            $this->picturePath = $pictureFile->getFilename();

        }
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

     public function setPictureSize(?int $pictureSize): void
    {
        $this->pictureSize = $pictureSize;
    }

    public function getPictureSize(): ?int
    {
        return $this->pictureSize;
    }

    public function getTicketLink(): ?string
    {
        return $this->ticketLink;
    }

    public function setTicketLink(?string $ticketLink): self
    {
        $this->ticketLink = $ticketLink;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }





}
