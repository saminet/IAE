<?php

namespace Gestion\UEBundle\Controller;

use Gestion\UEBundle\Entity\UE;
use Gestion\NiveauBundle\Entity\Niveau;
use Gestion\FiliereBundle\Entity\Filiere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gestion\UEBundle\Form\UEType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Gestion\UEBundle\Entity\Tag;
use Gestion\MatiereBundle\Entity\Matiere;
use Gestion\UEBundle\Form\StringToTagsTransformer;
use Gestion\UEBundle\Form\TagsType;
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

    public function newAction()
    {

        $em = $this->container->get('doctrine')->getEntityManager();
        $matiere= $em->getRepository('GestionMatiereBundle:Matiere')->findAll();
        $niveau= $em->getRepository('GestionNiveauBundle:Niveau')->findAll();
        $filiere= $em->getRepository('GestionFiliereBundle:Filiere')->findAll();

        return $this->render('GestionUEBundle:Default:ajouter.html.twig', array('matiere' => $matiere,'niveau' => $niveau,'filiere' => $filiere));
    }

    public function validerUEAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $unites= $em->getRepository('GestionUEBundle:UE')->findAll();
        //on crée un nouvelle unité
        $unite = new UE();
        $nomUnite=$request->get('nomUnite');
        $coef=$request->get('Coef');
        $credit=$request->get('Credit');
        $niveau=$request->get('niveau');
        $matiere= array();

        $matiere=$request->get('nomMatiere');

        if(!is_null($matiere)){
            foreach($matiere as $matiere) {
                $matiere=$request->get('nomMatiere');
            }
        }

        //var_dump($nomUnite,$coef,$credit,$matiere,$niveau);die('hello !!');

        $em = $this->getDoctrine()->getManager();

        $unite->setIntitule($nomUnite);
        $unite->setMatiere($matiere);
        $unite->setCoeffUnite($coef);
        $unite->setCreditUnite($credit);
        //$unite->setIdNiveau($niveau);
        $em->persist($unite);
        $em->flush();
        //on rend la vue
        return $this->render('GestionUEBundle:Default:listeUE.html.twig',array('unite' => $unites));
    }


    public function ajouterAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $niveau = $em->getRepository('GestionNiveauBundle:Niveau')->findAll();
        $filiere = $em->getRepository('GestionFiliereBundle:Filiere')->findAll();
        //on crée un nouveau etudiant
        $ue = new UE();
        //on recupere le formulaire
        $form = $this->createForm(UEType::class,$ue);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em->persist($ue);
                $em->flush();
                return $this->redirect($this->generateUrl('Liste_UE'));
            }
        }
        //on rend la vue
        return $this->render('GestionUEBundle:Default:ajouterUE.html.twig', array('niveau' => $niveau, 'filiere' => $filiere));
    }

    public function modifierUEAction($id, Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
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
            'unite' => $ue,
            'form'   => $formView,
        ));
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

    public function unitesAction()
    {   $filieres=null;
        $niveaux= $this->getDoctrine()->getEntityManager()->getRepository('GestionNiveauBundle:Niveau')->findAll();
        $matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        return $this->render('GestionUEBundle:Default:ajouter.html.twig', array('niveau'=>$niveaux, 'matiere'=>$matiere,
            'filiere'=>$filieres
        ));

    }

    public function ajaxGetFilieresAction(Request $request) {
        // Get the niveau ID
        $id = $request->query->get('niveau_id');
        $result = array();
        // Return a list of filiere, based on the selected niveau
        $repo = $this->getDoctrine()->getEntityManager()->getRepository('GestionFiliereBundle:Filiere');
        $filieres = $repo->findBy(array('niveau' => $id), array('intitule' => 'asc'), array('intitule' => 'asc'));
        foreach ($filieres as $filiere) {
            $result[$filiere->getIntitule()] = $filiere->getId();
        }
        return new JsonResponse($result);
    }


    public function saveAction(Request $request)
    {
        $selectedniveau=$request->get('NameNiveau');
        //$selectedniveau='1 ère année Finance';
        $selectedFiliere=$request->get('NameFiliere');

        $repository1=$this->getDoctrine()->getRepository('GestionNiveauBundle:Niveau');
        $selectedNiveau=$repository1->createQueryBuilder('e')->where('e.nomNiveau = :nomNiveauVarr')->setParameter('nomNiveauVarr', $selectedniveau)->getQuery()->getResult();
        //recherche de l'objet niveau selon id
        //var_dump($selectedNiveau); die('Hello');

        $Niveaux= $this->getDoctrine()->getRepository('GestionNiveauBundle:Niveau')->find($selectedNiveau[0]->getId());

        //determiner les objets filiere selon le niveau selected
        $repository2=$this->getDoctrine()->getRepository('GestionFiliereBundle:Filiere');
        $filieres=$repository2->createQueryBuilder('e')->where('e.niveau = :nomniveauVarr')->setParameter('nomniveauVarr', $Niveaux)->getQuery()->getResult();

        $niveau= $this->getDoctrine()->getEntityManager()->getRepository('GestionNiveauBundle:Niveau')->findAll();

        return $this->render('GestionUEBundle:Default:ajouter.html.twig', array('niveau'=>$niveau,'filre'=>$filieres));
    }



}
