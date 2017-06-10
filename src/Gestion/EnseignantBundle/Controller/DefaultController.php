<?php

namespace Gestion\EnseignantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gestion\EnseignantBundle\Form\EnseignantType;
use Gestion\EnseignantBundle\Form\EnseignantEditType;
use Gestion\EnseignantBundle\Entity\Enseignant;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionEnseignantBundle:Default:index.html.twig');
    }

    public function listEnseignantAction()
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $enseignants = $em->getRepository('GestionEnseignantBundle:Enseignant')->findAll();

        return $this->container->get('templating')->renderResponse('GestionEnseignantBundle:Default:listeEns.html.twig',array(
                'enseignant' => $enseignants, 'user' => $usr)
        );
    }

    public function infosEnseignantAction($id)
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $enseignants= $em->getRepository('GestionEnseignantBundle:Enseignant')->find($id);
        return $this->container->get('templating')->renderResponse('GestionEnseignantBundle:Default:infosEnseignant.html.twig',
            array(
                'enseignant' => $enseignants, 'user' => $usr
            ));
    }

    public function profilEnseignantAction(Request $request)
    {

        $user = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($user);
        $login = $user->getUsername();

        //var_dump($selectedIdEtudiant, $Semestre);die('Hello');
        $repository1=$this->getDoctrine()->getRepository('GestionEnseignantBundle:Enseignant');
        $SelectedEns=$repository1->createQueryBuilder('e')->where('e.login = :idEnseignant')->setParameter('idEnseignant', $login)->getQuery()->getResult();
        //var_dump($SelectedEns);die('Hello');
        return $this->render('GestionEnseignantBundle:Default:profilEnseignant.html.twig', array(
            'user' => $user, 'enseignant' => $SelectedEns));
    }

    public function EditProfilEnseignantAction($id, Request $request)
    {
        $Old_user=$request->get('username');
        $Old_usr=$request->get('idEns');
        $oldPwd=$request->get('oldPwd');

        $em = $this->container->get('doctrine')->getEntityManager();
        $enseignant= $em->getRepository('GestionEnseignantBundle:Enseignant')->find($id);
        if (null === $enseignant) {
            throw new NotFoundHttpException("L'enseignant d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(EnseignantEditType::class, $enseignant);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            $username = $donnee->getLogin();
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $dateNaisPerson=$donnee->getDateNaissance();
            $dateNaisPers=$request->get('dateNaisEns');
            //var_dump($username,$Old_usr,$password,$email,$Old_user);die('Hello');
            //ajout des paramètres username et password dans la table 'fos_user'
            $userManager = $this->get('fos_user.user_manager');
            $usr = $userManager->findUserByUsername($Old_usr);

                $usr->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $usr->setPassword($hash);
                $usr->setEmail($email);
                $userManager->updateUser($usr);

            if (null === $dateNaisPerson) {
                $date = new \DateTime($dateNaisPers);
                $enseignant->setDateNaissance($date);
            }
            if (null === $password) {
                $enseignant->setPassword($oldPwd);
            }
            $em->persist($enseignant);
            $em->flush();

            return $this->redirectToRoute('DashboardEnseignant', array('id' => $enseignant->getId()));
        }
        $user = $this->getUser();
        return $this->render('GestionEnseignantBundle:Default:modifierProfilEnseignant.html.twig', array(
            'enseignant' => $enseignant,
            'form'   => $formView, 'oldUser' => $Old_user, 'user' => $user
        ));
    }

    public function ajouterEnseignantAction(Request $request)
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $enseignant = new Enseignant();
        //on recupere le formulaire
        $form = $this->createForm(EnseignantType::class,$enseignant);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){

                $donnee = $form->getData();
                $etat = $donnee->getEtat();
                $username = $donnee->getLogin();
                $password = $donnee->getPassword();
                $email = $donnee->getEmail();
                $roles = 'ROLE_ENSEIGNANT';
                //var_dump($username,$password,$email,$roles);die('Hello');
                //créer un compte pour l'enseignant
                $userManager = $this->get('fos_user.user_manager');
                $user = $userManager->createUser();
                if ($etat=='Inactif') {
                    $user->setUsername($username);
                    $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                    $user->setPassword($hash);
                    $user->setEmail($email);
                    $user->setRoles(array($roles));
                    $user->setEnabled(false);
                    $userManager->updateUser($user);
                    $this->getDoctrine()->getManager()->flush();
                }
                else{
                    $user->setUsername($username);
                    $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                    $user->setPassword($hash);
                    $user->setEmail($email);
                    $user->setRoles(array($roles));
                    $user->setEnabled(true);
                    $userManager->updateUser($user);
                    $this->getDoctrine()->getManager()->flush();
                }

                $em->persist($enseignant);
                $em->flush();
                return $this->redirect($this->generateUrl('Liste_enseignant',array('user' => $usr)));
            }
        }
        //on rend la vue
        return $this->render('GestionEnseignantBundle:Default:ajouterEnseignant.html.twig',array(
            'form' => $formView, 'user' => $usr) );
    }

    public function verifLoginAction(Request $request)
    {
        $pseudo = $request->query->get('pseudo');
        //var_dump($pseudo,$email);die('Hello');
        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        // Pour récupérer la liste de tous les utilisateurs
        $pseuudo =$userManager->findUserByUsername($pseudo);
        //var_dump($res);die('Hello');
        if(Count($pseuudo)>0) {
            echo "<span style=\"color:#D91E18;\">Le pseudo <span style=\"color:#0000C0;\">$pseudo</span> est déjà pris.</span>";
        }
        else
        {
            echo "<span style=\"color:#00d95a;\">Le pseudo <span style=\"color:#0000C0;\">$pseudo</span>  est disponible.</span>";
        }
        exit();
    }

    public function verifEmailAction(Request $request)
    {
        $email =  $request->query->get('mail');
        //var_dump($pseudo,$email);die('Hello');
        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        // Pour récupérer la liste de tous les utilisateurs
        $Mail =$userManager->findUserBy(array('email' => $email));
        //var_dump($res);die('Hello');

        if(Count($Mail)>0) {
            echo "<span style=\"color:#D91E18;\">L'email <span style=\"color:#0000C0;\">$email</span> est déjà pris. </span>";
        }
        else
        {
            echo "<span style=\"color:#00d95a;\">L'email <span style=\"color:#0000C0;\">$email</span> est disponible.</span></span>";
        }
        exit();
    }


    public function modifierEnseignantAction($id, Request $request)
    {
        $usrs = $this->getUser();
        $Old_user=$request->get('username');
        $Old_usr=$request->get('idEns');
        $oldPwd=$request->get('oldPwd');

        $em = $this->container->get('doctrine')->getEntityManager();
        $enseignant= $em->getRepository('GestionEnseignantBundle:Enseignant')->find($id);
        if (null === $enseignant) {
            throw new NotFoundHttpException("L'enseignant d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(EnseignantType::class, $enseignant);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            $username = $donnee->getLogin();
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $dateNaisPerson=$donnee->getDateNaissance();
            $dateNaisPers=$request->get('dateNaisEns');
            //var_dump($username,$Old_usr,$password,$email,$Old_user);die('Hello');
            //ajout des paramètres username et password dans la table 'fos_user'
            $userManager = $this->get('fos_user.user_manager');
            $usr = $userManager->findUserByUsername($Old_usr);

            if ($etat=='Inactif') {
                $usr->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $usr->setPassword($hash);
                $usr->setEmail($email);
                $usr->setEnabled(false);
                $userManager->updateUser($usr);
            }
            else{
                $usr->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $usr->setPassword($hash);
                $usr->setEmail($email);
                $usr->setEnabled(true);
                $userManager->updateUser($usr);

                if (null === $dateNaisPerson) {
                    $date = new \DateTime($dateNaisPers);
                    $enseignant->setDateNaissance($date);
                }
                if (null === $password) {
                    $enseignant->setPassword($oldPwd);
                }
                $em->persist($enseignant);
                $em->flush();
            }
            return $this->redirectToRoute('Liste_enseignant', array('id' => $enseignant->getId(), 'user' => $usrs));
        }

        return $this->render('GestionEnseignantBundle:Default:modifierEnseignant.html.twig', array(
            'enseignant' => $enseignant,
            'form'   => $formView, 'oldUser' => $Old_user, 'user' => $usrs
        ));
    }

    public function supprimerEnseignantAction(Request $request, $id)
    {
        $usrs = $this->getUser();
        $username=$request->get('username');
        //var_dump($username);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $this->getDoctrine()->getRepository('GestionEnseignantBundle:Enseignant')->find($id);
        if (!$etudiant)
        {
            throw new NotFoundHttpException("Enseignant non trouvé");
        }

        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        $usr = $userManager->findUserByUsername($username);
        $em->remove($usr);

        $em->remove($etudiant);
        $em->flush();
        return new RedirectResponse($this->container->get('router')->generate('Liste_enseignant',array('user' => $usrs)));
    }


}
