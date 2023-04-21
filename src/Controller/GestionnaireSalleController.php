<?php

namespace App\Controller;

use App\Entity\GestionnaireSalle;
use App\Form\GestionnaireSalleType;
use App\Repository\GestionnaireSalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/gestionnaire-salle')]
class GestionnaireSalleController extends AbstractController
{
    #[Route('/', name: 'app_gestionnaire_salle_index', methods: ['GET'])]
    public function index(GestionnaireSalleRepository $gestionnaireSalleRepository): Response
    {
        $gestionnaireSalles = $gestionnaireSalleRepository->findAll();

        if (!$this->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $gestionnaireSalle = $user->getGestionnaireSalle();
        }

        return $this->render('gestionnaire_salle/index.html.twig', [
            'gestionnaire_salles' => $gestionnaireSalles,
        ]);
    }

    #[Route('/new', name: 'app_gestionnaire_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GestionnaireSalleRepository $gestionnaireSalleRepository): Response
    {
        $gestionnaireSalle = new GestionnaireSalle();
        $form = $this->createForm(GestionnaireSalleType::class, $gestionnaireSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gestionnaireSalleRepository->save($gestionnaireSalle, true);

            return $this->redirectToRoute('app_gestionnaire_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestionnaire_salle/new.html.twig', [
            'gestionnaire_salle' => $gestionnaireSalle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestionnaire_salle_show', methods: ['GET'])]
    public function show(GestionnaireSalle $gestionnaireSalle): Response
    {
        return $this->render('gestionnaire_salle/show.html.twig', [
            'gestionnaire_salle' => $gestionnaireSalle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestionnaire_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GestionnaireSalle $gestionnaireSalle, GestionnaireSalleRepository $gestionnaireSalleRepository): Response
    {
        // sauf si l'utilisateur est un admin,
        // on compare son gestionnaire de salle et le gestionnaire de salle qu'il veut modifier
        // s'ils ne coincident pas, le user n'a pas accès à cette page
        if (!$this->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $userGestionnaireSalle = $user->getGestionnaireSalle();

            if ($userGestionnaireSalle->getId() != $gestionnaireSalle->getId()) {
                throw new AccessDeniedException();
            }
        }

        $form = $this->createForm(GestionnaireSalleType::class, $gestionnaireSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gestionnaireSalleRepository->save($gestionnaireSalle, true);

            return $this->redirectToRoute('app_gestionnaire_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestionnaire_salle/edit.html.twig', [
            'gestionnaire_salle' => $gestionnaireSalle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestionnaire_salle_delete', methods: ['POST'])]
    public function delete(Request $request, GestionnaireSalle $gestionnaireSalle, GestionnaireSalleRepository $gestionnaireSalleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gestionnaireSalle->getId(), $request->request->get('_token'))) {
            $gestionnaireSalleRepository->remove($gestionnaireSalle, true);
        }

        return $this->redirectToRoute('app_gestionnaire_salle_index', [], Response::HTTP_SEE_OTHER);
    }
}
