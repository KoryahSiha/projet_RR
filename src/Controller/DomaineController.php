<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Form\DomaineType;
use App\Repository\DomaineRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/domaine')]
class DomaineController extends AbstractController
{
    /**
     * Cette fonction affiche tous les domaines.
     *
     * @param DomaineRepository $domaineRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'app_domaine_index', methods: ['GET'])]
    public function index(DomaineRepository $domaineRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // permet de paginer
        $domaines = $paginator->paginate(
            // appelle la méthode findAllDomaines() du DomaineRepository pour récupérer tous les objets Domaine et les trier par ordre croissant des noms
            $domaineRepository->findAllDomaines(), /* C'est la requête, non le résultat */
            $request->query->getInt('page', 1), /* numéro de page */
            10 /* limite par page */
        );

        return $this->render('pages/domaine/index.html.twig', [
            'domaines' => $domaines,
        ]);
    }

    /**
     * Ce contrôleur montre un formulaire qui crée un domaine.
     *
     * @param Request $request
     * @param DomaineRepository $domaineRepository
     * @return Response
     */
    #[Route('/new', name: 'app_domaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DomaineRepository $domaineRepository): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domaineRepository->save($domaine, true);

            return $this->redirectToRoute('app_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/domaine/new.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_domaine_show', methods: ['GET'])]
    public function show(Domaine $domaine): Response
    {
        return $this->render('pages/domaine/show.html.twig', [
            'domaine' => $domaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_domaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Domaine $domaine, DomaineRepository $domaineRepository): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domaineRepository->save($domaine, true);

            return $this->redirectToRoute('app_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/domaine/edit.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_domaine_delete', methods: ['POST'])]
    public function delete(Request $request, Domaine $domaine, DomaineRepository $domaineRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->request->get('_token'))) {
            $domaineRepository->remove($domaine, true);
        }

        return $this->redirectToRoute('app_domaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
