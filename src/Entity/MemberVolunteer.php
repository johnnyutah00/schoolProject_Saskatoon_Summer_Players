<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberVolunteerRepository")
 */
class MemberVolunteer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="memberVolunteer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank(
     *
     *     message = "You must enter your age"
     * )
     */
    private $age;

    /**
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $volunteerOptions = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z\d\s_!.,-]+$/",
     *     match = true,
     *     message = "Characters like *%^$@& aren't allowed here"
     * )
     * @Assert\Length(
     *      max = 250,
     *      maxMessage = "Please keep additional information 250 characters or less."
     * )
     */
    private $additionalInfo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getVolunteerOptions(): ?array
    {
        return $this->volunteerOptions;
    }

    public function setVolunteerOptions(array $volunteerOptions): self
    {
        $this->volunteerOptions = $volunteerOptions;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }
}
