<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/salle')]
class SalleController extends AbstractController
{
    /**
     * Cette fonction affiche toutes les salles.
     *
     * @param SalleRepository $salleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'app_salle_index', methods: ['GET'])]
    public function index(SalleRepository $salleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // permet de paginer
        $salles = $paginator->paginate(
            // appelle la méthode findAllSalles() du SalleRepository pour récupérer tous les objets Salle et les trier par ordre croissant des noms
            $salleRepository->findAllSalles(), /* C'est la requête, non le résultat */
            $request->query->getInt('page', 1), /* numéro de page */
            10 /* limite par page*/
        );

        return $this->render('pages/salle/index.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/new', name: 'app_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SalleRepository $salleRepository): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salleRepository->save($salle, true);

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_show', methods: ['GET'])]
    public function show(Salle $salle): Response
    {
        return $this->render('pages/salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, SalleRepository $salleRepository): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salleRepository->save($salle, true);

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, SalleRepository $salleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->request->get('_token'))) {
            $salleRepository->remove($salle, true);
        }

        return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
    }
}
