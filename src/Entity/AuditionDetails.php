<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AuditionDetailsRepository")
 */
class AuditionDetails
{
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     *
     */
    private $playTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $playImage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $auditionDetails;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $directorMessage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $howToAudition;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $characterSummeries;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $noteFromDirector;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $auditionMaterials;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * AuditionDetails constructor.
     * AuditionDetails is used for displaying information for auditions on a web page.
     * @param $playTitle - Title of show used as the title of the web page
     * @param $playImage - Advert image of the show used as a visual aid on the page
     * @param $auditionDetails - Specifics for the show such as time and place
     * @param $directorMessage - specific message from the director
     * @param $howToAudition - details on how to go about auditioning for the specific show
     * @param $synopsis - synopsis about the show to be displayed on the page
     * @param $characterSummeries - information on the specific characters that are a part of the show
     * @param $noteFromDirector - additional information that the director may want to share with members
     * @param $auditionMaterials - sheet music, etc. for the play that members will need
     */
    public function __construct($playTitle, $playImage, $auditionDetails='', $directorMessage='', $howToAudition='', $synopsis='', $characterSummeries='', $noteFromDirector='', $auditionMaterials='')
    {
        $this->playTitle = $playTitle;
        $this->playImage = $playImage;
        $this->auditionDetails = $auditionDetails;
        $this->directorMessage = $directorMessage;
        $this->howToAudition = $howToAudition;
        $this->synopsis = $synopsis;
        $this->characterSummeries = $characterSummeries;
        $this->noteFromDirector = $noteFromDirector;
        $this->auditionMaterials = $auditionMaterials;
    }


    public function getPlayTitle(): ?string
    {
        return $this->playTitle;
    }

    public function setPlayTitle(string $playTitle): self
    {
        $this->playTitle = $playTitle;

        return $this;
    }

    public function getPlayImage(): ?string
    {
        return $this->playImage;
    }

    public function setPlayImage(?string $playImage): self
    {
        $this->playImage = $playImage;

        return $this;
    }

    public function getAuditionDetails(): ?string
    {
        return $this->auditionDetails;
    }

    public function setAuditionDetails(?string $auditionDetails): self
    {
        $this->auditionDetails = $auditionDetails;

        return $this;
    }

    public function getDirectorMessage(): ?string
    {
        return $this->directorMessage;
    }

    public function setDirectorMessage(?string $directorMessage): self
    {
        $this->directorMessage = $directorMessage;

        return $this;
    }

    public function getHowToAudition(): ?string
    {
        return $this->howToAudition;
    }

    public function setHowToAudition(?string $howToAudition): self
    {
        $this->howToAudition = $howToAudition;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getCharacterSummeries(): ?string
    {
        return $this->characterSummeries;
    }

    public function setCharacterSummeries(?string $characterSummeries): self
    {
        $this->characterSummeries = $characterSummeries;

        return $this;
    }

    public function getNoteFromDirector(): ?string
    {
        return $this->noteFromDirector;
    }

    public function setNoteFromDirector(?string $noteFromDirector): self
    {
        $this->noteFromDirector = $noteFromDirector;

        return $this;
    }

    public function getAuditionMaterials(): ?string
    {
        return $this->auditionMaterials;
    }

    public function setAuditionMaterials(?string $auditionMaterials): self
    {
        $this->auditionMaterials = $auditionMaterials;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
