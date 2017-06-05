<?php

namespace Gestion\EntrepriseBundle\Controller;

use Gestion\EntrepriseBundle\Entity\Entreprise;
use Gestion\EntrepriseBundle\Form\EntrepriseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionEntrepriseBundle:Default:index.html.twig');
    }

    public function listEntrepAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $entreprise = $em->getRepository('GestionEntrepriseBundle:Entreprise')->findAll();
        $usr = $this->getUser();

        return $this->container->get('templating')->renderResponse('GestionEntrepriseBundle:Default:listEntrep.html.twig', array(
                'entreprise' => $entreprise, 'user' => $usr)
        );

    }

    public function ajoutEntrepAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $entreprise = new Entreprise();
        //on recupere le formulaire
        $form = $this->createForm(EntrepriseType::class,$entreprise);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($entreprise);
            $em->flush();
            $usr = $this->getUser();
            return $this->redirect($this->generateUrl('liste_entreprise',array('user' => $usr)));
        }
        //on rend la vue
        return $this->render('GestionEntrepriseBundle:Default:ajouterEntrep.html.twig',array(
            'form'   => $formView));
    }

    public function editEntrepAction($id, Request $request)
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $entreprise= $em->getRepository('GestionEntrepriseBundle:Entreprise')->find($id);
        if (null === $entreprise) {
            throw new NotFoundHttpException("L'Entreprise d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $em->flush();
            return $this->redirectToRoute('liste_entreprise', array('id' => $entreprise->getId(), 'user' => $usr));
        }
        return $this->render('GestionEntrepriseBundle:Default:modifierEntrep.html.twig', array(
            'entreprise' => $entreprise,
            'form' => $formView, 'user' => $usr
        ));
    }

    public function suppEntrepAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $entreprise= $this->getDoctrine()->getRepository('GestionEntrepriseBundle:Entreprise')->find($id);
        if (!$entreprise)
        {
            throw new NotFoundHttpException("Entreprise non trouvé");
        }
        $em->remove($entreprise);
        $em->flush();
        $usr = $this->getUser();
        return new RedirectResponse($this->get('router')->generate('liste_entreprise',array('user' => $usr)));
    }

}
