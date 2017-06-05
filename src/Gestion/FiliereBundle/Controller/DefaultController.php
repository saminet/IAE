<?php

namespace Gestion\FiliereBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gestion\FiliereBundle\Entity\Filiere;
use Gestion\FiliereBundle\Form\FiliereType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionFiliereBundle:Default:index.html.twig');
    }

    public function listFilieresAction()
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $filiere = $em->getRepository('GestionFiliereBundle:Filiere')->findAll();

        return $this->container->get('templating')->renderResponse('GestionFiliereBundle:Default:listeFiliere.html.twig',array(
                'filiere' => $filiere,'user' => $usr)
        );
    }


    public function ajouterFiliereAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $filiere = new Filiere();
        //on recupere le formulaire
        $form = $this->createForm(FiliereType::class,$filiere);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em->persist($filiere);
                $em->flush();
                $usr = $this->getUser();
                return $this->redirect($this->generateUrl('liste_filieres',array('user' => $usr)));
            }
        }
        //on rend la vue
        return $this->render('GestionFiliereBundle:Default:ajouterFiliere.html.twig',array(
            'form' => $formView) );
    }


    public function modifierFilieresAction($id, Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $filiere= $em->getRepository('GestionFiliereBundle:Filiere')->find($id);
        if (null === $filiere) {
            throw new NotFoundHttpException("La filière d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(FiliereType::class, $filiere);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre filière
            $em->flush();
            $usr = $this->getUser();
            return $this->redirectToRoute('liste_filieres', array('id' => $filiere->getId(), 'user' => $usr));
        }

        return $this->render('GestionFiliereBundle:Default:modifierFilieres.html.twig', array(
            'filiere' => $filiere,
            'form'   => $formView,
        ));
    }

    public function supprimerFilieresAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $filiere= $this->getDoctrine()->getRepository('GestionFiliereBundle:Filiere')->find($id);
        if (!$filiere)
        {
            throw new NotFoundHttpException("Aucune filiere trouvé");
        }
        $em->remove($filiere);
        $em->flush();
        $usr = $this->getUser();
        return new RedirectResponse($this->container->get('router')->generate('liste_filieres',array('user' => $usr)));
    }

}
