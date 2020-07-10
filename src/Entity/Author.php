<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 * @ORM\Table(name="author", indexes={@ORM\Index(columns={"name"}, flags={"fulltext"})})
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birth;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThan(propertyPath="birth", message="La date de mort doit etre supperieur à la date de naissance")
     */
    private $death;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Writer", mappedBy="author")
     */
    private $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
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

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(?\DateTimeInterface $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    public function getDeath(): ?\DateTimeInterface
    {
        return $this->death;
    }

    public function setDeath(?\DateTimeInterface $death): self
    {
        $this->death = $death;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Writer[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Writer $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setAuthor($this);
        }

        return $this;
    }

    public function removeParticipation(Writer $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getAuthor() === $this) {
                $participation->setAuthor(null);
            }
        }

        return $this;
    }
}
