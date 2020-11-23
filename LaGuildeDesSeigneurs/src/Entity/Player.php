<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 * @ORM\Table(name="players")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 16,
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 64,
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $mirian;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Modification;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *      min = 40,
     *      max = 40,
     * )
     */
    private $identifier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $character_played;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMirian(): ?int
    {
        return $this->mirian;
    }

    public function setMirian(int $mirian): self
    {
        $this->mirian = $mirian;

        return $this;
    }

     /**
     *   Converts the entity in an array
     */

    public function toArray(){
        return get_object_vars($this);
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->Creation;
    }

    public function setCreation(\DateTimeInterface $Creation): self
    {
        $this->Creation = $Creation;

        return $this;
    }

    public function getModification(): ?\DateTimeInterface
    {
        return $this->Modification;
    }

    public function setModification(?\DateTimeInterface $Modification): self
    {
        $this->Modification = $Modification;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getCharacterPlayed()
    {
        return $this->character_played;
    }

    public function setCharacterPlayed($character_played): self
    {
        $this->character_played = $character_played;

        return $this;
    }
}
