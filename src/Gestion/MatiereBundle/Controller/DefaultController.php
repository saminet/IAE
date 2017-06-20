<?php

namespace Gestion\MatiereBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Gestion\MatiereBundle\Entity\Matiere;
use Gestion\MatiereBundle\Form\MatiereType;
use Gestion\AbsenceBundle\Entity\Classe;
use Symfony\Component\HttpFoundation\RedirectResponse;
class DefaultController extends Controller
{
    public function indexAction()
    {
        $classes= $this->getDoctrine()->getEntityManager()->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        $user = $this->getUser();
        return $this->render('GestionMatiereBundle:Default:CrudMatiere.html.twig', array(
            'user' => $user,'matieres'=>$matiere, 'classe'=>$classes ));

    }

    public function listMatiereAction()
    {
        $matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        $user = $this->getUser();
        return $this->render('GestionMatiereBundle:Default:listMatiere.html.twig', array(
            'user' => $user,'matieres'=>$matiere));
    }


    public function AjoutMatiereAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau matière
        $matiere = new Matiere();
        //on recupere le formulaire
        $form = $this->createForm(MatiereType::class,$matiere);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($matiere);
            $em->flush();
            return new RedirectResponse($this->container->get('router')->generate('listMatiere',array('user' => $user)));
        }
        //on rend la vue
        return $this->render('GestionMatiereBundle:Default:ajoutMatiere.html.twig', array('user' => $user, 'form' => $formView ));
    }

    public function modifierMatiereAction($id, Request $request)
    {

        $user = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $matiere= $em->getRepository('GestionMatiereBundle:Matiere')->find($id);
        if (null === $matiere) {
            throw new NotFoundHttpException("La matière d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(MatiereType::class, $matiere);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre Matiere
            $em->flush();
            return new RedirectResponse($this->container->get('router')->generate('listMatiere',array('user' => $user)));
        }

        return $this->render('GestionMatiereBundle:Default:modifierMatiere.html.twig', array(
            'user' => $user,'matiere'=>$matiere, 'form' => $formView ));

    }

    public function deleteMatiereAction($id)
    {
        $something = $this->getDoctrine()
            ->getRepository('GestionMatiereBundle:Matiere')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($something);
        $em->flush();

        $matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        $user = $this->getUser();
        return new RedirectResponse($this->container->get('router')->generate('listMatiere',array('user' => $user)));

    }

}
