<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PasswordResetRepository")
 */
class PasswordReset
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Member", inversedBy="passwordReset", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\Column(type="string")
     */
    private $recoveryValue;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeGenerated;

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

    public function getRecoveryValue(): ?string
    {
        return $this->recoveryValue;
    }

    public function setRecoveryValue(string $recoveryValue): self
    {
        $this->recoveryValue = $recoveryValue;

        return $this;
    }

    public function getTimeGenerated(): ?\DateTimeInterface
    {
        return $this->timeGenerated;
    }

    public function setTimeGenerated(\DateTimeInterface $timeGenerated): self
    {
        $this->timeGenerated = $timeGenerated;

        return $this;
    }
}
