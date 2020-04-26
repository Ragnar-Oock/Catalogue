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
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="editions")
     */
    private $authors;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="editions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Collec", mappedBy="editions")
     */
    private $collecs;

    /**
     * @ORM\Column(type="integer")
     */
    private $disponibility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editor", inversedBy="editions")
     */
    private $editor;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->collecs = new ArrayCollection();
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

    /**
     * @return Collection|author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

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

    public function getCollection(): ?collection
    {
        return $this->collection;
    }

    public function setCollection(?collection $collection): self
    {
        $this->collection = $collection;

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
            $collec->addEdition($this);
        }

        return $this;
    }

    public function removeCollec(Collec $collec): self
    {
        if ($this->collecs->contains($collec)) {
            $this->collecs->removeElement($collec);
            $collec->removeEdition($this);
        }

        return $this;
    }

    public function getDisponibility(): ?int
    {
        return $this->disponibility;
    }

    public function setDisponibility(int $disponibility): self
    {
        $this->disponibility = $disponibility;

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
}
