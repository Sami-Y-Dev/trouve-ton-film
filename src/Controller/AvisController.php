<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Movie;
use App\Form\AvisType;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AvisController extends AbstractController
{
    #[Route('/movie/{id}/avis', name: 'add_avis')]
    #[IsGranted('ROLE_USER')]
    public function addAvis(Movie $movie, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        foreach ($movie->getAvis() as $avis) {
            if ($avis->getUser() === $user) {
                $this->addFlash('warning', 'Vous avez déjà laissé un avis pour ce film.');
                return $this->redirectToRoute('movie_detail', ['id' => $movie->getId()]);
            }
        }

        $avis = new Avis();
        $avis->setMovie($movie);
        $avis->setUser($user);
        $avis->setDateAvis(new \DateTimeImmutable());

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Avis ajouté avec succès.');
            return $this->redirectToRoute('movie_detail', ['id' => $movie->getId()]);
        }

        return $this->render('avis/add.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }
}
