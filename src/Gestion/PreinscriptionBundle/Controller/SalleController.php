<?php

namespace Gestion\PreinscriptionBundle\Controller;
use Gestion\PreinscriptionBundle\Entity\Preinscrit;
use Gestion\PreinscriptionBundle\Entity\Etudiant;
use Gestion\PreinscriptionBundle\Entity\Salle;
use Gestion\PreinscriptionBundle\Entity\Task;
use Gestion\PreinscriptionBundle\Form\EtudiantForm;
use Gestion\PreinscriptionBundle\Form\EtudiantType;
use Gestion\PreinscriptionBundle\Form\SalleType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalleController extends Controller
{

    public function indexAction()
    {
        return $this->render('GestionAdminBundle:Default:index.html.twig');
    }


    public function listSallesAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $salle = $em->getRepository('GestionPreinscriptionBundle:Salle')->findAll();

        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:listeSalles.html.twig',array(
                'salle' => $salle)
        );
    }

    public function ajouterSalleAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $salle = new Salle();
        //on recupere le formulaire
        $form = $this->createForm(SalleType::class,$salle);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em->persist($salle);
                $em->flush();
                return $this->redirect($this->generateUrl('list_Salles'));
            }
        }
        //on rend la vue
        return $this->render('GestionPreinscriptionBundle:Default:ajouterSalle.html.twig',array(
            'form' => $formView) );
    }

    public function supprimerSalleAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $salle= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Salle')->find($id);
        if (!$salle)
        {
            throw new NotFoundHttpException("Aucune salle trouvé");
        }
        $em->remove($salle);
        $em->flush();
        return new RedirectResponse($this->container->get('router')->generate('list_Salles'));
    }

    public function modifierSalleAction($id, Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $salle= $em->getRepository('GestionPreinscriptionBundle:Salle')->find($id);
        if (null === $salle) {
            throw new NotFoundHttpException("La sale d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(SalleType::class, $salle);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->flush();

            return $this->redirectToRoute('list_Salles', array('id' => $salle->getId()));
        }

        return $this->render('GestionPreinscriptionBundle:Default:modifierSalle.html.twig', array(
            'salle' => $salle,
            'form'   => $formView,
        ));
    }

}
