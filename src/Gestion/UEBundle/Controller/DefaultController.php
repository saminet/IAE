<?php

namespace Gestion\UEBundle\Controller;

use Gestion\UEBundle\Entity\UE;
use Gestion\UEBundle\Entity\Tag;
use Gestion\MatiereBundle\Entity\Matiere;
use Gestion\NiveauBundle\Entity\Niveau;
use Gestion\UEBundle\Form\UEType;
use Gestion\UEBundle\Form\StringToTagsTransformer;
use Gestion\UEBundle\Form\TagsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionUEBundle:Default:index.html.twig');
    }

    public function listAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $ue = $em->getRepository('GestionUEBundle:UE')->findAll();

        return $this->container->get('templating')->renderResponse('GestionUEBundle:Default:listeUE.html.twig',array(
                'unites' => $ue)
        );
    }

    public function AjouterUEAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        //$filieres=null;
        //$niveaux= $this->getDoctrine()->getEntityManager()->getRepository('GestionNiveauBundle:Niveau')->findAll();
        //$matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        $unite = new UE();
        //on recupere le formulaire
        $form = $this->createForm(UEType::class, $unite);
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $donnee = $form->getData();
            $intitule = $donnee->getIntitule();
            $niveau = $donnee->getNiveau();
            $filiere = $donnee->getFiliere();
            $matieres = $donnee->getMatieres();
            var_dump($intitule,$niveau,$niveau,$filiere,$matieres);die('Hello');
            //$user->setRoles(array($roles));
            // Inutile de persister ici, Doctrine connait déjà notre unité d'enseignement
            $em->flush();
            return $this->redirectToRoute('Liste_UE');
        }
        return $this->render('GestionUEBundle:Default:ajouter.html.twig', array('unite' => $unite, 'form'   => $formView));
    }

    public function modifierUEAction($id, Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        //$filieres=null;
        //$niveaux= $this->getDoctrine()->getEntityManager()->getRepository('GestionNiveauBundle:Niveau')->findAll();
        //$matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        $ue= $em->getRepository('GestionUEBundle:UE')->find($id);
        if (null === $ue) {
            throw new NotFoundHttpException("L'unité d'id ".$id." n'existe pas.");
            //on génère le html du formulaire crée
        }
        //on recupere le formulaire
        $form = $this->createForm(UEType::class, $ue);
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre unité d'enseignement
            $em->flush();

            return $this->redirectToRoute('Liste_UE');
        }

        return $this->render('GestionUEBundle:Default:modifierUE.html.twig', array(
            'unite' => $ue, 'form'   => $formView));
    }

    public function supprimerUEAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $ue= $this->getDoctrine()->getRepository('GestionUEBundle:UE')->find($id);
        if (!$ue)
        {
            throw new NotFoundHttpException("Aucune unité trouvé");
        }
        $em->remove($ue);
        $em->flush();
        return new RedirectResponse($this->container->get('router')->generate('Liste_UE'));
    }

    public function ajaxGetFilieresAction(Request $request) {
        // Get the Niveau ID
        $id = $request->query->get('niveau_id');
        $result = array();
        // Return a list of filiere, based on the selected niveau
        $repo = $this->getDoctrine()->getEntityManager()->getRepository('GestionFiliereBundle:Filiere');
        $filieres = $repo->findBy(array('niveau' => $id), array('intitule' => 'asc'));
        foreach ($filieres as $filiere) {
            $result[$filiere->getIntitule()] = $filiere->getId();
        }
        return new JsonResponse($result);
    }
}
