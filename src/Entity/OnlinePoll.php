<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OnlinePollRepository")
 */
class OnlinePoll
{
    /**
     * Unique identifier for the OnlinePoll
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name for the poll
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\NotBlank(
     *
     *     message = "Name is required"
     * )
     *
     * @Assert\Length(
     *
     *     max = 200,
     *     maxMessage = "The name must not be longer than 200 characters"
     * )
     */
    private $Name;

    /**
     * Description for the poll or the question asked.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\NotBlank(
     *
     *     message = "Description is required"
     * )
     *
     * @Assert\Length(
     *
     *     max = 500,
     *     maxMessage = "The description must not be longer than 500 characters"
     * )
     */
    private $Description;

    /**
     * Array of option strings that a board member can vote on.
     *
     * @ORM\Column(type="array", nullable=true)
     *
    */
    private $Options = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->Options;
    }

    public function setOptions(array $Options): self
    {
        $this->Options = $Options;

        return $this;
    }
}
