<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id=1;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $name='Athelleen';

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $surname='Guerrière des flammes';

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $caste='Guerrier';

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $knowledge='Cartographie';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $intelligence=90;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $life=15;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $image;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCaste(): ?string
    {
        return $this->caste;
    }

    public function setCaste(?string $caste): self
    {
        $this->caste = $caste;

        return $this;
    }

    public function getKnowledge(): ?string
    {
        return $this->knowledge;
    }

    public function setKnowledge(?string $knowledge): self
    {
        $this->knowledge = $knowledge;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(?int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getLife(): ?int
    {
        return $this->life;
    }

    public function setLife(?int $life): self
    {
        $this->life = $life;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     *   Converts the entity in an array
     */

    public function toArray(){
        return get_object_vars($this);
    }
}
