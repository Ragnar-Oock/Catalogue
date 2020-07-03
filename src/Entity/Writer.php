<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WriterRepository")
 */
class Writer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ParticipationType", inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participationType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Edition", inversedBy="writers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipationType(): ?ParticipationType
    {
        return $this->participationType;
    }

    public function setParticipationType(?ParticipationType $participationType): self
    {
        $this->participationType = $participationType;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): self
    {
        $this->edition = $edition;

        return $this;
    }
}
