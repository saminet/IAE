<?php

namespace Gestion\PaiementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gestion\PaiementBundle\Entity\PaiementEtudiant;
use Gestion\AbsenceBundle\Entity\Classe;
use Gestion\AbsenceBundle\Entity\Groupe;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
class PaymentEtudiantController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionPaiementBundle:Default:index.html.twig');
    }

    public function listePaiementAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $paiement = $em->getRepository('GestionPaiementBundle:PaiementEtudiant')->findAll();
        $Classes = $em->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $groupe = null;
        $user = $this->getUser();
        return $this->container->get('templating')->renderResponse('GestionPaiementBundle:Default:listePaiementEtudiant.html.twig', array(
                'paiement' => $paiement, 'user' => $user, 'classes' => $Classes, 'groupe' => $groupe)
        );
    }

    public function listePaiementGroupeAction(Request $request)
    {
        $user = $this->getUser();
        $selectedIdGroupe = $request->get('idgroupe');
        $em = $this->container->get('doctrine')->getEntityManager();
        $Classes = $em->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $groupe = null;
        //var_dump($selectedIdGroupe);die('Hello');

        $em = $this->getDoctrine()->getManager();

        //liste des groupes à partir la liste
        $repository1 = $this->getDoctrine()->getRepository('GestionAbsenceBundle:Groupe');
        $SelectedGroupes = $repository1->createQueryBuilder('e')->where('e.id = :idGroupe')->setParameter('idGroupe', $selectedIdGroupe)->getQuery()->getResult();
        //var_dump($SelectedGroupes);die('Hello');

        //liste des étudiants selon le groupe
        $repository2 = $this->getDoctrine()->getRepository('GestionPaiementBundle:PaiementEtudiant');
        $SelectedPayment = $repository2->createQueryBuilder('e')->where('e.groupe = :idGroupe')->setParameter('idGroupe', $SelectedGroupes)->getQuery()->getResult();
        //var_dump($SelectedPayment);die('Hello');

        $paiement = $em->getRepository('GestionPaiementBundle:PaiementEtudiant')->findAll();
        $user = $this->getUser();
        return $this->render('GestionPaiementBundle:Default:ResultPaiementEtudiant.html.twig', array(
            'user' => $user, 'paiement' => $paiement, 'SelectedPayment' => $SelectedPayment, 'classes' => $Classes, 'groupe' => $groupe));
    }

    public function PaiementSuppAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('GestionPaiementBundle:PaiementEtudiant')->find($id);
        $em->remove($note);
        $em->flush();
        //on rend la vue
        return new RedirectResponse($this->container->get('router')->generate('gestion_paiement_etudiant', array('user' => $user)));
    }

}