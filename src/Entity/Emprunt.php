<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEmprunt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]  // Un emprunt doit toujours avoir un livre associé
    private ?Livre $livre = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]  // Un emprunt doit toujours avoir un utilisateur associé
    private ?User $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $dateRetour): static
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function getUtilisateur(): ?user
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?user $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    // Méthode pour vérifier si l'emprunt est toujours en cours
    public function isEmpruntEnCours(): bool
    {
        // Si la date de retour est non définie ou si la date de retour est dans le futur
        return $this->dateRetour === null || $this->dateRetour > new \DateTime();
    }

    // Méthode pour obtenir un résumé de l'emprunt (livre et utilisateur)
    public function getResume(): string
    {
        // Vérification que les informations de livre et utilisateur sont présentes
        $livreTitre = $this->livre ? $this->livre->getTitre() : 'Livre non disponible';
        $utilisateurNom = $this->utilisateur ? $this->utilisateur->getNom() : 'Utilisateur non disponible';
        
        return sprintf(
            'Emprunt de "%s" par %s (ID: %d)',
            $livreTitre,
            $utilisateurNom,
            $this->utilisateur ? $this->utilisateur->getId() : 'Inconnu'
        );
    }


}