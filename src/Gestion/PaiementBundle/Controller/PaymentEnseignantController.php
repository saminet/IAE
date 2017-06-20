<?php

namespace Gestion\PaiementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gestion\PaiementBundle\Entity\PaiementEnseignant;
use Gestion\PaiementBundle\Form\PaiementEnseignantType;
use Gestion\NoteBundle\Entity\Note;
use Gestion\NoteBundle\Form\NoteType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
class PaymentEnseignantController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionPaiementBundle:Default:index.html.twig');
    }

    public function listPaiementEnseignantAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $paiement = $em->getRepository('GestionPaiementBundle:PaiementEnseignant')->findAll();
        $user = $this->getUser();
        return $this->render('GestionPaiementBundle:Default:payerEnseignant.html.twig', array('user' => $user, 'paiements' => $paiement));
    }

    public function deletePaiementEnseignantAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('GestionPaiementBundle:PaiementEnseignant')->find($id);
        $em->remove($note);
        $em->flush();
        //on rend la vue
        $usr = $this->getUser();
        return new RedirectResponse($this->container->get('router')->generate('gestion_paiement_enseignant',array('user' => $usr)));
    }


}
