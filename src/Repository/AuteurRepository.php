<?php 
// src/Repository/AuteurRepository.php
namespace App\Repository;

use App\Entity\Auteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    // Vous pouvez ajouter ici des méthodes personnalisées pour manipuler les auteurs si nécessaire.
}
