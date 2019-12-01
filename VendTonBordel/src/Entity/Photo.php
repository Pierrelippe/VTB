<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Mettez une image valide"
     * )
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="profilPhoto")
     */
    private $User_photo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonces", inversedBy="photo")
     */
    private $AnnoncePhoto;

    public function __construct()
    {
        $this->User_photo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserPhoto(): Collection
    {
        return $this->User_photo;
    }

    public function addUserPhoto(User $userPhoto): self
    {
        if (!$this->User_photo->contains($userPhoto)) {
            $this->User_photo[] = $userPhoto;
            $userPhoto->setProfilPhoto($this);
        }

        return $this;
    }

    public function removeUserPhoto(User $userPhoto): self
    {
        if ($this->User_photo->contains($userPhoto)) {
            $this->User_photo->removeElement($userPhoto);
            // set the owning side to null (unless already changed)
            if ($userPhoto->getProfilPhoto() === $this) {
                $userPhoto->setProfilPhoto(null);
            }
        }

        return $this;
    }

    public function getAnnoncePhoto(): ?Annonces
    {
        return $this->AnnoncePhoto;
    }

    public function setAnnoncePhoto(?Annonces $AnnoncePhoto): self
    {
        $this->AnnoncePhoto = $AnnoncePhoto;

        return $this;
    }
}
