<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\SuggestedShowRepository")
 */
class SuggestedShow
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "Show titles must be three or more characters",
     *      maxMessage = "Show titles must under 255 characters"
     * )
     * @Assert\NotBlank(
     *     message = "Please enter a show name."
     * )
     *
     */
    private $suggestedTitle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuggestedTitle(): ?string
    {
        return $this->suggestedTitle;
    }

    public function setSuggestedTitle(string $suggestedTitle): self
    {
        $this->suggestedTitle = $suggestedTitle;

        return $this;
    }
}
