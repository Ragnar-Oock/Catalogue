<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EditionRepository")
 */
class Edition
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $issn;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $isbn;

    /**
     * @ORM\Column(type="integer")
     */
    private $inventoryNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pages;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="editions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="editions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Collec", inversedBy="editions")
     */
    private $collecs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editor", inversedBy="editions")
     */
    private $editor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Writer", mappedBy="edition", orphanRemoval=true)
     */
    private $writers;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="edition")
     */
    private $reservations;

    public function __construct()
    {
        $this->collecs = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssn(): ?string
    {
        return $this->issn;
    }

    public function setIssn(?string $issn): self
    {
        $this->issn = $issn;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getInventoryNumber(): ?int
    {
        return $this->inventoryNumber;
    }

    public function setInventoryNumber(int $inventoryNumber): self
    {
        $this->inventoryNumber = $inventoryNumber;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getTome(): ?string
    {
        return $this->tome;
    }

    public function setTome(?string $tome): self
    {
        $this->tome = $tome;

        return $this;
    }

    public function getPages(): ?string
    {
        return $this->pages;
    }

    public function setPages(?string $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getDocument(): ?document
    {
        return $this->document;
    }

    public function setDocument(?document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getType(): ?type
    {
        return $this->type;
    }

    public function setType(?type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Collec[]
     */
    public function getCollecs(): Collection
    {
        return $this->collecs;
    }

    public function addCollec(Collec $collec): self
    {
        if (!$this->collecs->contains($collec)) {
            $this->collecs[] = $collec;
        }

        return $this;
    }

    public function removeCollec(Collec $collec): self
    {
        if ($this->collecs->contains($collec)) {
            $this->collecs->removeElement($collec);
        }

        return $this;
    }

    public function getEditor(): ?editor
    {
        return $this->editor;
    }

    public function setEditor(?editor $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function __toString()
    {
        return $this->inventoryNumber . ' - ' . $this->document;
    }

    /**
     * @return Collection|Writer[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Writer $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
            $writer->setEdition($this);
        }

        return $this;
    }

    public function removeWriter(Writer $writer): self
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
            // set the owning side to null (unless already changed)
            if ($writer->getEdition() === $this) {
                $writer->setEdition(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setEdition($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getEdition() === $this) {
                $reservation->setEdition(null);
            }
        }

        return $this;
    }

}
