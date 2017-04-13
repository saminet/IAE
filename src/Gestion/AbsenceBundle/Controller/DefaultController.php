<?php

namespace Gestion\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {   $groupes=null;
        $matieres=null;
        $Etudiants=null;
        $classes= $this->getDoctrine()->getEntityManager()->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $user = $this->getUser();
        return $this->render('GestionAbsenceBundle:Default:index.html.twig', array(
            'user' => $user,'classes'=>$classes,'groupes'=>$groupes,'dates'=>$matieres,'etudiants'=>$Etudiants
        ));
    }

    public function saveAction(Request $request)
    {
        $selectedClass=$request->get('NameClass');
        $selectedClass='1 ère année Finance';
        $selectedGroupe=$request->get('NameGroupe');
        $selectedGroupe=$request->get('NameGroupe');
        $selectedMatiere=$request->get('NameMatiere');


        $repository1=$this->getDoctrine()->getRepository('GestionAbsenceBundle:Classe');
        $SelectedClasse=$repository1->createQueryBuilder('e')->where('e.intitule = :nomclassVarr')->setParameter('nomclassVarr', $selectedClass)->getQuery()->getResult();

        //recherche de l'objet classe selon id
        $classe= $this->getDoctrine()->getRepository('GestionAbsenceBundle:Classe')->find($SelectedClasse[0]->getId());

        //determiner les objets groupes selon classe
        $repository2=$this->getDoctrine()->getRepository('GestionAbsenceBundle:Groupe');
        $SelectedGroupes=$repository2->createQueryBuilder('e')->where('e.classe = :classe')->setParameter('classe', $classe)->getQuery()->getResult();
//*************************

//*************************

        $classes= $this->getDoctrine()->getEntityManager()->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $Etudiants=null;
        $selectedMatieree=null;

        $user = $this->getUser();
        return $this->render('GestionAbsenceBundle:Default:index.html.twig', array(
            'user' => $user,'classes'=>$classes,'groupes'=>$SelectedGroupes,'dates'=>$selectedMatieree,'etudiants'=>$Etudiants
        ));

    }


    public function Save_QueryGroupeAction(Request $request)
    {
        $selectedGroupe=$request->get('NameGroupe');


        //determiner les objets groupe selon groupe selected
        $repository3=$this->getDoctrine()->getRepository('GestionAbsenceBundle:Groupe');
        $SelectedGroupee=$repository3->createQueryBuilder('e')->where('e.intitule = :nomgroupeVarr')->setParameter('nomgroupeVarr', $selectedGroupe)->getQuery()->getResult();

        //recherche de l'objet groupe selon id

        //echo $selectedGroupe;
        $groupe= $this->getDoctrine()->getRepository('GestionAbsenceBundle:Groupe')->find($SelectedGroupee[0]->getId());

        //determiner les matieres selon groupe
        $repository4=$this->getDoctrine()->getRepository('GestionAbsenceBundle:Date');
        $selectedMatieree=$repository4->createQueryBuilder('e')->where('e.groupe = :groupe')->setParameter('groupe', $groupe)->getQuery()->getResult();

        //liste etudiants
        $repository5=$this->getDoctrine()->getRepository('GestionAbsenceBundle:AnneScolaire');
        $Etudiants=$repository5->createQueryBuilder('e')->where('e.groupe = :groupe')->setParameter('groupe', $groupe)->getQuery()->getResult();


        $classes= $this->getDoctrine()->getEntityManager()->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $SelectedGroupes=$this->getDoctrine()->getEntityManager()->getRepository('GestionAbsenceBundle:Groupe')->findAll();

        $user = $this->getUser();
        return $this->render('GestionAbsenceBundle:Default:index.html.twig', array(
            'user' => $user,'classes'=>$classes,'groupes'=>$SelectedGroupes,'dates'=>$selectedMatieree,'etudiants'=>$Etudiants
        ));
    }
}
