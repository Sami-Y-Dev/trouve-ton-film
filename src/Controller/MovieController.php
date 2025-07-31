<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Form\MovieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'movie_list')]
    public function list(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movie/{id}', name: 'movie_detail')]
    public function detail(int $id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);

        if (!$movie) {
            throw $this->createNotFoundException('Film non trouvé');
        }

        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/admin/movie', name: 'admin_movie_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();
        return $this->render('admin/movie/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/admin/movie/new', name: 'admin_movie_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($movie);
            $em->flush();

            $this->addFlash('success', 'Film créé avec succès.');
            return $this->redirectToRoute('admin_movie_list');
        }

        return $this->render('admin/movie/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/movie/{id}/edit', name: 'admin_movie_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Movie $movie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Film mis à jour avec succès.');
            return $this->redirectToRoute('admin_movie_list');
        }

        return $this->render('admin/movie/edit.html.twig', ['form' => $form->createView(), 'movie' => $movie]);
    }

    #[Route('/admin/movie/{id}/delete', name: 'admin_movie_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Movie $movie, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $em->remove($movie);
            $em->flush();

            $this->addFlash('success', 'Film supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_movie_list');
    }
}
