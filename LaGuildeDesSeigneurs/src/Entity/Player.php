<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=16, name="gls_firstname")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 16,
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64, name="gls_lastname")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 64,
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="text", length=255, nullable=true, name="gls_email")
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="integer", name="gls_mirian")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $mirian;

    /**
     * @ORM\Column(type="datetime", name="gls_creation")
     */
    private $Creation;

    /**
     * @ORM\Column(type="datetime", name="gls_modification")
     */
    private $Modification;

    /**
     * @ORM\Column(type="string", length=40, name="gls_identifier")
     * @Assert\Length(
     *      min = 40,
     *      max = 40,
     * )
     */
    private $identifier;

    /**
     * @ORM\Column(type="integer", nullable=true, name="gls_character_played")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $character_played;

    /**
     * @ORM\OneToMany(targetEntity=Character::class, mappedBy="player")
     */
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

   

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

    public function toArray(bool $expand = true)
    {
        $player =  get_object_vars($this);
        if ($expand && null !== $this->getCharacters()) {
            $characters = array();
            foreach ($this->getCharacters() as $character) {
                $characters[] = $character->toArray(false);
            }
            $player['characters'] = $characters;
        }
        //specific data
        if (null !== $player['Creation']) {
            $player['Creation'] = $player['Creation']->format('Y-m-d H:i:s');
        }
        if (null !== $player['Modification']) {
            $player['Modification'] = $player['Modification']->format('Y-m-d H:i:s');
        }
        return $player;
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

    public function getCharacterPlayed(): ?int
    {
        return $this->character_played;
    }

    public function setCharacterPlayed(?int $character_played): self
    {
        $this->character_played = $character_played;

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setPlayer($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getPlayer() === $this) {
                $character->setPlayer(null);
            }
        }

        return $this;
    }
}
