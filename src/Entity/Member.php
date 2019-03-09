<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 * @UniqueEntity(
 *   fields = {"userName"},
 *   message = "A user with that email already exists"
 * )
 *
 *
 */
class Member implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * This is the id that will uniquely identify the member in the databasse
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *
     *     message = "First name must be between 1-20 characters"
     * )
     * @Assert\Length(
     *      min = 1,
     *      max = 20,
     *      minMessage = "First name must be between 1-20 characters without spaces",
     *      maxMessage = "First name must be between 1-20 characters without spaces"
     * )
     *
     * This is the members firstName that will be stored in the database
     */
    private $firstName;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\IsTrue(message="You must agree to the terms and conditions")
     */
    private $membershipAgreement;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *
     *     message = "Last name must be between 1-20 characters without spaces"
     * )
     *
     * @Assert\Length(
     *      min = 1,
     *      max = 20,
     *      minMessage = "Last name must be between 1-20 characters without spaces",
     *      maxMessage = "Last name must be between 1-20 characters without spaces"
     * )
     *
     * This is the members lastName that will be stored in the database
     */
    private $lastName;


    /**
     * @ORM\Column(type="string", length=50)
     *@Assert\Email(
     *      message = "Email must be in standard email format"
     * )
     * @Assert\NotBlank(
     *      message = "Email is required"
     * )
     *
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "Email exceeds 50 characters"
     * )
     * This is the members userName/email that will be stored in the database
     */
    private $userName;


    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 6,
     *      max = 4096,
     *      minMessage = "Password must consist of 6-20 alpha characters",
     *      maxMessage = "Password must consist of 6-20 alpha characters"
     * )
     *
     * @Assert\Regex(
     *     pattern = "/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/",
     *     match = true,
     *     message = "Password must consist of one upper-case, one-lower-case, one number"
     * )
     *
     * This is the members password that will be hashed
     */
    private $password;




    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Choice({"Individual", "Family" })
     *
     * This the type of membership that the current member has, specific allowed values are "Individual" & "Family"
     */
    private $memberType;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Choice({"1-year Paid Membership", "Subscription" })
     *
     * This is the membership option which will specify if the membership is auto-renewed every year,
     * specific allowed values are "1-year Paid Membership" & "Subscription"
     */
    private $memberOption;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * This property stores the last date that this member paid for their membership, should be set whenever they pay.
     * it is stored in UNIX integer time
     */
    private $lastDatePaid;



    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *      message = "City is required"
     * )
     *
     *@Assert\Length(
     *      max = 100,
     *      maxMessage = "City must be under 100 characters"
     * )
     * This is the City that is stored in the database
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(
     *      message = "Postal Code is required"
     * )
     *
     * @Assert\Regex(
     *     pattern="/^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$/",
     *     match=true,
     *     message="Postal Code should be in the format of A2A3B3 or A2A 3B3"
     * )
     *
     * This is the postalCode that is stored in the database
     */
    private $postalCode;


    /**
     * @Assert\Choice(choices={"AB","BC","MB","NB","NL","NT","NS","NU","ON","PE","QC","SK", "YT"},
     *      message="Need to enter Province")
     *
     * @ORM\Column(type="string", length=100)
     * This is the province that is stored in the database
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Company is invalid"
     * )
     *
     * This is the company that is stored in the database
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * This is the phone that is stored in the database
     *
     *
     * @Assert\Regex(
     *     pattern="/^\(\d{3}\)\s\d{3}\-\d{4}$/",
     *     match=true,
     *     message="Please enter a 10-digit phone number"
     * )
     *
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank(
     *      message = "Address Line One is required"
     * )
     *
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Billing address must be under 100 characters"
     * )
     *
     * This is the AddressLineOne that is stored in the database
     */
    private $addressLineOne;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "AddressLineTwo must be under 100 characters"
     * )
     *
     * This is the $addressLineTwo that is stored in the database
     */
    private $addressLineTwo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $memberGroups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MemberVolunteer", mappedBy="member", cascade={"persist", "remove"})
     */
    private $memberVolunteer;


    /**
     * Member constructor.
     * @param $id - autoincrementing database id
     * @param $firstName
     * @param $lastName
     * @param $memberGroups - Board Member, member, volunteer, etc.
     * @param $membershipAgreement - If they have agreed or not (should always be true)
     * @param $userName - their email
     * @param $enc_password - the encrypted password
     * @param $memberType - For paying members - family membership, single, etc.
     * @param $memberOption - Whether it is auto renewing or one time payment
     * @param null $lastDatePaid - last date they paid for membership
     * @param $city
     * @param $postalCode
     * @param $province
     * @param $company
     * @param $phone
     * @param $addLine1 - address line 1
     * @param $addLine2 - address line 2
     */
    public function __construct()
    {

    }



    /**
     * @ORM\Column(type="string", length=20)
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PasswordReset", mappedBy="member", cascade={"persist", "remove"})
     */
    private $passwordReset;



    /**
     * Gets the ide from the Member Object.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the firstName from the member object.
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Gets the lastName from the Member object
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Sets the LastName to the Member Object
     * @param string $lastName
     * @return Member
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Gets the username from the Member object
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->userName;
    }

     public function getMembershipAgreement(): ?bool
    {
        return $this->membershipAgreement;
    }

    public function setMembershipAgreement(bool $membershipAgreement): self
    {
        $this->membershipAgreement = $membershipAgreement;
        return $this;
	}

    /**
     * Sets the UserName/email to the entered in value
     * @param string $userName
     * @return Member
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }


    /**
     * Gets the password from a Member object.
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the password to the entered value
     * @param string $password - entered password
     * @return Member
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [$this->roles];
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * We are using bCrypt so we do not need a salt, it is built in
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * This method is automatically called during log in, we do not want it to erase credentials when logging in.
     */
    public function eraseCredentials()
    {
    }


    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMemberType(): ?string
    {
        return $this->memberType;
    }

    public function setMemberType(string $memberType): self
    {
        $this->memberType = $memberType;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getMemberOption(): ?string
    {
        return $this->memberOption;
    }

    public function setMemberOption(string $memberOption): self
    {
        $this->memberOption = $memberOption;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddressLineOne(): ?string
    {
        return $this->addressLineOne;
    }

    public function setAddressLineOne(?string $addressLineOne): self
    {
        $this->addressLineOne = $addressLineOne;

        return $this;
    }

    public function getAddressLineTwo(): ?string
    {
        return $this->addressLineTwo;
    }

    public function setAddressLineTwo(?string $addressLineTwo): self
    {
        $this->addressLineTwo = $addressLineTwo;

        return $this;
    }

    public function getLastDatePaid(): ?int
    {
        return $this->lastDatePaid;
    }

    public function setLastDatePaid(int $lastDatePaid): self
    {
        $this->lastDatePaid = $lastDatePaid;


        return $this;
    }


    public function getMemberGroups(): ?string
    {
        return $this->memberGroups;
    }

    public function setMemberGroups(string $memberGroups): self
    {
        $this->memberGroups = $memberGroups;

        return $this;
    }

    public function getMemberVolunteer(): ?MemberVolunteer
    {
        return $this->memberVolunteer;
    }

    public function setMemberVolunteer(MemberVolunteer $memberVolunteer): self
    {
        $this->memberVolunteer = $memberVolunteer;

        // set the owning side of the relation if necessary
        if ($this !== $memberVolunteer->getMember())
        {
            $memberVolunteer->setMember($this);
        }
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->userName,
            $this->password
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->userName, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function setRoles(string $roles)
    {
        $this->roles = $roles;


    }


    public function __toString() : string
    {
        return $this->userName;
    }

    public function getPasswordReset(): ?PasswordReset
    {
        return $this->passwordReset;
    }

    public function setPasswordReset(PasswordReset $passwordReset): self
    {
        $this->passwordReset = $passwordReset;

        // set the owning side of the relation if necessary
        if ($this !== $passwordReset->getMember()) {
            $passwordReset->setMember($this);
        }

        return $this;
    }


}
