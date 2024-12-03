<?php
namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupérer tous les livres depuis la base de données
        $livres = $em->getRepository(Livre::class)->findAll();

        // Récupérer tous les genres
        $genres = $em->getRepository(Genre::class)->findAll();

        // Liste des livres populaires (dynamique avec les livres de la base de données)
        $popularBooks = [];
        foreach ($livres as $livre) {
            $popularBooks[] = [
                'id' => $livre->getId(),
                'title' => $livre->getTitre(),
                'author' => $livre->getAuteur()->getNom(),
                'image' => 'default-book.jpg', // Remplacez ceci par une logique d'image si nécessaire
            ];
        }

        return $this->render('home/index.html.twig', [
            'popular_books' => $popularBooks,  // Passer les livres populaires à la vue
            'genres' => $genres,  // Passer la liste des genres à la vue
        ]);
    }

    #[Route('/home/livre/{id}', name: 'home_livre_show')]
    public function show(Livre $livre): Response
    {
        // Afficher un livre spécifique
        return $this->render('livre/show.html.twig', [
            'livre' => $livre, // Passer l'objet livre à la vue
        ]);
    }

    #[Route('/auteurs', name: 'auteur_index')]
    public function auteur(EntityManagerInterface $em): Response
    {
        // Récupération de tous les auteurs depuis la base de données
        $auteurs = $em->getRepository(Auteur::class)->findAll();

        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurs,  // Passer les auteurs à la vue
        ]);
    }

    #[Route('/genre/{id}', name: 'genre_show')]
    public function showGenre(int $id, EntityManagerInterface $em): Response
    {
        // Récupérer le genre avec l'ID depuis la base de données
        $genre = $em->getRepository(Genre::class)->find($id);

        if (!$genre) {
            throw $this->createNotFoundException('Genre non trouvé');
        }

        return $this->render('genre/show.html.twig', [
            'genre' => $genre,  // Passer le genre à la vue
        ]);
    }

    #[Route('/genres', name: 'genre_index')]
    public function indexGenres(EntityManagerInterface $em): Response
    {
        // Récupérer tous les genres depuis la base de données
        $genres = $em->getRepository(Genre::class)->findAll();

        return $this->render('genre/index.html.twig', [
            'genres' => $genres,  // Passer les genres à la vue
        ]);
    }

    #[Route('/search', name: 'search_books')]
    public function searchBooks(Request $request, EntityManagerInterface $em): Response
    {
        $searchTerm = $request->query->get('search');
        
        if ($searchTerm) {
            // Recherche des livres avec le terme de recherche
            $livres = $em->getRepository(Livre::class)->createQueryBuilder('l')
                ->where('l.titre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
    
            // Message d'alerte si aucun livre n'est trouvé
            $message = (count($livres) === 0) ? 'Aucun livre trouvé pour "' . $searchTerm . '".' : '';
        } else {
            // Si aucun terme n'est fourni
            $livres = [];
            $message = 'Veuillez entrer un terme de recherche.';
        }
    
        return $this->render('home/search.html.twig', [
            'livres' => $livres,
            'searchTerm' => $searchTerm,
            'message' => $message,  // Passer le message à la vue
        ]);
    }
}
