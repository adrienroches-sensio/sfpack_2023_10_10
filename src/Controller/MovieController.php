<?php

namespace App\Controller;

use App\Entity\Movie as MovieEntity;
use App\Form\MovieType;
use App\Model\Movie;
use App\Omdb\Client\OmdbApiClientInterface;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class MovieController extends AbstractController
{
    public function __construct(
        private readonly OmdbApiClientInterface $omdbApiClient,
    ) {
    }

    #[Route(
        '/movies',
        name: 'app_movies_list',
        methods: ['GET']
    )]
    public function list(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/list.html.twig', [
            'movies' => Movie::fromEntities($movieRepository->listAll()),
        ]);
    }

    #[Route(
        '/movies/{slug}',
        name: 'app_movies_details',
        requirements: [
            'slug' => MovieEntity::SLUG_FORMAT,
        ],
        methods: ['GET']
    )]
    public function detailsFromDatabase(MovieRepository $movieRepository, string $slug): Response
    {
        $movie = $movieRepository->getBySlug($slug);

        return $this->render('movie/details.html.twig', [
            'movie' => Movie::fromEntity($movie),
            'can_edit' => true,
        ]);
    }

    #[Route(
        '/movies/{imdbID}',
        name: 'app_movies_details_omdb',
        requirements: [
            'imdbID' => 'tt.{1,50}',
        ],
        methods: ['GET']
    )]
    public function detailsFromOmdb(string $imdbID): Response
    {
        $movie = $this->omdbApiClient->getByImdbId($imdbID);

        return $this->render('movie/details.html.twig', [
            'movie' => Movie::fromOmdb($movie),
            'can_edit' => false,
        ]);
    }

    #[Route(
        '/movies/new',
        name: 'app_movies_new',
        methods: ['GET', 'POST']
    )]
    #[Route(
        '/movies/{slug}/edit',
        name: 'app_movies_edit',
        requirements: [
            'slug' => MovieEntity::SLUG_FORMAT,
        ],
        methods: ['GET', 'POST']
    )]
    public function newOrEdit(
        Request $request,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager,
        string|null $slug = null
    ): Response {
        $movieEntity = new MovieEntity();
        if (null !== $slug) {
            $movieEntity = $movieRepository->getBySlug($slug);
        }

        $movieForm = $this->createForm(MovieType::class, $movieEntity);
        $movieForm->handleRequest($request);

        if ($movieForm->isSubmitted() && $movieForm->isValid()) {
            $entityManager->persist($movieEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_movies_details', ['slug' => $movieEntity->getSlug()]);
        }

        return $this->render('movie/new_or_edit.html.twig', [
            'movie_form' => $movieForm,
            'editing_movie' => null !== $slug ? Movie::fromEntity($movieEntity) : null,
        ]);
    }
}
