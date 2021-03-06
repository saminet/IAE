<?php

namespace Gestion\PreinscriptionBundle\Controller;
use Gestion\PreinscriptionBundle\Entity\Preinscrit;
use Gestion\PreinscriptionBundle\Entity\Etudiant;
use Gestion\PreinscriptionBundle\Entity\Parents;
use Gestion\NiveauBundle\Entity\Niveau;
use Gestion\FiliereBundle\Entity\Filiere;
use Gestion\PreinscriptionBundle\Form\EtudiantType;
use Gestion\PreinscriptionBundle\Form\EtudiantEditType;
use Gestion\PreinscriptionBundle\Form\ParentsType;
use Gestion\PreinscriptionBundle\Form\ParentsEditType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('GestionPreinscriptionBundle:Default:index.html.twig');
    }

    public function importerPreinscritAction(Request $request)
    {
        $usr = $this->getUser();
        if (isset($_POST['submit'])) {

            $file=$_FILES['file']['tmp_name'];

            $handle = fopen($file, "r");
            while(($fileop = fgetcsv($handle,1000, ";")) !== false){
                $em = $this->getDoctrine()->getManager();

                $preinscrit1 = new Preinscrit();
                $preinscrit1->setNom($fileop[1]);
                $preinscrit1->setPrenom($fileop[2]);
                $preinscrit1->setDateNaissance(new \DateTime($fileop[3]));
                $preinscrit1->setLieuNaissance($fileop[4]);
                $preinscrit1->setNationalite($fileop[5]);
                $preinscrit1->setVille($fileop[6]);
                $preinscrit1->setNumCinPass($fileop[7]);
                $preinscrit1->setSexe($fileop[8]);
                $preinscrit1->setAdresse($fileop[9]);
                $preinscrit1->setTel($fileop[10]);
                $preinscrit1->setEmail($fileop[11]);
                $preinscrit1->setDiplome($fileop[12]);
                $preinscrit1->setEtablissement($fileop[13]);
                $preinscrit1->setAnneeObtention(new \DateTime($fileop[14]));
                $preinscrit1->setMessage($fileop[15]);
                $preinscrit1->setNiveau($fileop[16]);
                $preinscrit1->setFormation($fileop[17]);
                $preinscrit1->setEtat($fileop[18]);
                $em->persist($preinscrit1);
                $verif = $em->getRepository('GestionPreinscriptionBundle:Preinscrit')->findOneBy(array('numCinPass' => $fileop[0]));
                if($verif){
                    echo 'personne déjà existante';
                }else{

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($preinscrit1);
                    $em->flush();
                }
            }
            $request->getSession()->getFlashBag()->add('success', 'Liste enregistrée.');
            return $this->redirect($this->generateUrl('Liste_preinscrits', array('id' => $preinscrit1->getId())));
        }
        return $this->render('GestionPreinscriptionBundle:Default:listePre.html.twig', array('user' => $usr));
    }


    public function newAction() {
        $em = $this->container->get('doctrine')->getEntityManager();

        $preinscrit1 = new Preinscrit();
        $preinscrit1->setNom('Jean');
        $preinscrit1->setPrenom('Michel');
        $preinscrit1->setDateNaissance(new \DateTime("2011-07-23 06:12:33"));
        $preinscrit1->setLieuNaissance('Aquitenne');
        $preinscrit1->setNationalite('Francaise');
        $preinscrit1->setVille('Paris');
        $preinscrit1->setNumCinPass('123990');
        $preinscrit1->setSexe('Masculain');
        $preinscrit1->setAdresse('Paris');
        $preinscrit1->setTel('123456');
        $preinscrit1->setEmail('michel88@gmail.com');
        $preinscrit1->setDiplome('Maitrise');
        $preinscrit1->setEtablissement('Sorbon');
        $preinscrit1->setAnneeObtention(new \DateTime("2011-07-23 06:12:33"));
        $preinscrit1->setMessage('Bonjour');
        $preinscrit1->setNiveau('1 ère année');
        $preinscrit1->setFormation('Licence Fondamentale en Management');
        $preinscrit1->setEtat('En Attente');
        $em->persist($preinscrit1);

        $em->flush();

        return new RedirectResponse($this->container->get('router')->generate('Liste_preinscrits'));

        $message = 'Insertion terminée : ';

        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:index.html.twig',
            array('message' => $message)
        );
    }

    public function listPreinscritAction()
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrits = $em->getRepository('GestionPreinscriptionBundle:Preinscrit')->findAll();

        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:listePre.html.twig',array('user' => $usr,
                'preinscrits' => $preinscrits)
        );
    }

    public function accepterPreinscritAction($id)
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrit= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);
        $preinscrit->setEtat('Vérifié');
        $em->persist($preinscrit);
        $em->flush();
        $attachment = \Swift_Attachment::fromPath('../web/uploads/frais-scolarite.pdf');
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Préinscription IAE')
            ->setFrom('jeap37208@gmail.com')
            ->setTo(''.$preinscrit->getEmail().'')
            ->setBody(
                'Bonjour Mr/Mme '.$preinscrit->getNom().' '.$preinscrit->getPrenom().',<br /> <br /> <br /> nous vous informons que votre demande d\'inscription à été bien accéptée 
                <br /><br />  Vous trouvez ci dessous toutes les informations pour compléter votre dossier ainsi que les frais de scolarités.<br /><br /> <br />Si vous avez quelque chose 
                à renseigner, merci de nous avoir contacter sur :<br /><br />Email :  iaetunis@gmail.com / contact@iaetunis.com <br /><br />Tél. : 94569697<br /><br />Fax : 71845269 E-mail <br /> <br /> <br /> <br /> <br /> <br />
                Cordialement','text/html'
            )
            ->attach($attachment);

        $type = $message->getHeaders()->get('Content-Type');
        $type->setParameter('charset', 'utf-8');
        $this->get('mailer')->send($message);


        return $this->render('GestionPreinscriptionBundle:Default:accepterPreinscrit.html.twig',array(
            'candidat' => $preinscrit, 'user' => $usr) );
    }

    public function validerPreinscritAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrit= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);

        $etudiant = new Etudiant();
        $etudiant->setNom($preinscrit->getNom());
        $etudiant->setPrenom($preinscrit->getPrenom());
        $etudiant->setDateNaissance($preinscrit->getDateNaissance());
        $etudiant->setLieuNaissance($preinscrit->getLieuNaissance());
        $etudiant->setNationalite($preinscrit->getNationalite());
        $etudiant->setVille($preinscrit->getVille());
        $etudiant->setNumCinPass($preinscrit->getNumCinPass());
        $etudiant->setSexe($preinscrit->getSexe());
        $etudiant->setAdresse($preinscrit->getAdresse());
        $etudiant->setTel($preinscrit->getTel());
        $etudiant->setEmail($preinscrit->getEmail());
        $etudiant->setDiplome($preinscrit->getDiplome());
        $etudiant->setEtablissement($preinscrit->getEtablissement());
        $etudiant->setAnneeObtention($preinscrit->getAnneeObtention());
        $etudiant->setClasse($preinscrit->getNiveau().' '.$preinscrit->getFormation());
        $etudiant->setEtat('Inactif');
        $etudiant->setLogin($preinscrit->getEmail());
        $etudiant->setPassword($preinscrit->getNumCinPass());

        $em->persist($etudiant);

        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername($preinscrit->getEmail());
        $hash = password_hash($preinscrit->getNumCinPass(),PASSWORD_BCRYPT,['cost' => 13]) ;
        $user->setPassword($hash);
        $user->setEmail($preinscrit->getEmail());
        $user->setEnabled(false);
        $roles = 'ROLE_ETUDIANT';
        $user->setRoles(array($roles));
        $userManager->updateUser($user);

        $em->remove($preinscrit);
        $em->flush();

        $attachment = \Swift_Attachment::fromPath('../web/uploads/frais-scolarite.pdf');
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Préinscription IAE')
            ->setFrom('jeap37208@gmail.com')
            ->setTo(''.$preinscrit->getEmail().'')
            ->setBody(
                'Bonjour Mr/Mme '.$preinscrit->getPrenom().',<br /> <br /> <br /> nous vous informons que votre inscription à été bien accéptée 
                <br /><br />  Vous Pouvez se connecter dans votre espace Membre avec les identifiants suivants :<br /><br />Login : '.$preinscrit->getEmail().' <br /><br />Mot De Passe : '.$preinscrit->getNumCinPass().'  <br />Si vous avez quelque chose 
                à renseigner, merci de nous avoir contacter sur :<br />Email :  iaetunis@gmail.com / contact@iaetunis.com <br /><br />Tél. : 94569697<br /><br />Fax : 71845269 E-mail <br /> <br /> <br /> <br /> <br /> <br />
                Cordialement','text/html'
            )
            ->attach($attachment);

        $type = $message->getHeaders()->get('Content-Type');
        $type->setParameter('charset', 'utf-8');

        $this->get('mailer')->send($message);
        $usr = $this->getUser();
        return $this->render('GestionPreinscriptionBundle:Default:valider_preinscrit.html.twig',array('user' => $usr,
            'etudiant' => $etudiant) );
    }

    public function refuserPreinscritAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrit= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);
        if (!$preinscrit)
        {
            throw new NotFoundHttpException("Candidat non trouvé");
        }
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Préinscription IAE')
            ->setFrom('jeap37208@gmail.com')
            ->setTo(''.$preinscrit->getEmail().'')
            ->setBody(
                'Bonjour Mr/Mme '.$preinscrit->getNom().',<br /> <br /> <br />
                Nous vous remercions de l’intérêt que vous avez manifesté vis-à-vis de notre université.<br /> <br />
                Après un examen attentif de votre dossier, nous avons le regret de vous informer que nous ne 
                pouvons pas retenir votre candidature <br />car votre profil ne répond pas aux critères exigés. 
                <br /><br />Nous sommes toujours ouvert à acceuillir votre candidature en cas d\'évolution de votre dossier  <br /><br />
                <br />Pour toutes informations, merci de nous contacter sur : <br /><br />
                Email :  iaetunis@gmail.com / contact@iaetunis.com <br /><br />
                Tél. : 94569697<br /><br />Fax : 71845269 E-mail <br /> <br /> <br /> <br /> <br /><br />
                Cordialement','text/html'
            );

        $type = $message->getHeaders()->get('Content-Type');
        $type->setParameter('charset', 'utf-8');
        $this->get('mailer')->send($message);
        $em->remove($preinscrit);
        $em->flush();
        $user = $this->getUser();
        return $this->render('GestionPreinscriptionBundle:Default:refuserPreinscrit.html.twig',array(
            'candidat' => $preinscrit, 'user' => $user) );
    }

    public function listEtudiantAction()
    {
        $usr = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiants = $em->getRepository('GestionPreinscriptionBundle:Etudiant')->findAll();
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:listEtd.html.twig',array(
                'etudiants' => $etudiants, 'user' => $usr)
        );
    }

    public function profilEtudiantAction(Request $request)
    {
        $user = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $usr = $userManager->findUserByUsername($user);
        $login = $usr->getUsername();

        //var_dump($selectedIdEtudiant, $Semestre);die('Hello');
        $repository1=$this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Etudiant');
        $SelectedEtud=$repository1->createQueryBuilder('e')->where('e.login = :idEtudiant')->setParameter('idEtudiant', $login)->getQuery()->getResult();
        //var_dump($SelectedEtud);die('Hello');
        return $this->render('GestionPreinscriptionBundle:Default:profilEtudiant.html.twig', array(
            'user' => $user, 'etudiant' => $SelectedEtud));
    }

    public function profilParentAction(Request $request)
    {

        $user = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $usr = $userManager->findUserByUsername($user);
        $login = $usr->getUsername();

        //var_dump($selectedIdEtudiant, $Semestre);die('Hello');
        $repository1=$this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Parents');
        $SelectedParent=$repository1->createQueryBuilder('e')->where('e.login = :idParent')->setParameter('idParent', $login)->getQuery()->getResult();
        //var_dump($SelectedEtud);die('Hello');
        return $this->render('GestionPreinscriptionBundle:Default:profilParent.html.twig', array(
            'user' => $user, 'parents' => $SelectedParent));
    }

    public function EditProfilParentAction($id, Request $request)
    {
        $users = $this->getUser();
        $Old_user=$request->get('username');
        $Old_usr=$request->get('idEtud');
        $oldPwd=$request->get('oldPwd');
        //var_dump($Old_user);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $parent= $em->getRepository('GestionPreinscriptionBundle:Parents')->find($id);
        if (null === $parent) {
            throw new NotFoundHttpException("Le parent d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(ParentsEditType::class, $parent);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $donnee = $form->getData();
            $username = $donnee->getLogin();
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $roles = 'ROLE_PARENTS';
            $dateNaisPerson=$donnee->getDateNaissance();
            $dateNaisPers=$request->get('dateNaisParnt');
            //var_dump($username,$Old_usr,$password,$email,$roles);die('Hello');
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($Old_usr);
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);

            if (null === $dateNaisPerson) {
                $date = new \DateTime($dateNaisPers);
                $parent->setDateNaissance($date);
            }
            $em->flush();
            return $this->redirectToRoute('DashboardParent', array('user' => $users));
        }
        return $this->render('GestionPreinscriptionBundle:Default:modifierProfilParent.html.twig', array(
            'parent' => $parent, 'form' => $formView, 'oldUser' => $Old_user, 'user' => $users));
    }

    public function ajouterEtudiantAction(Request $request)
    {
        $usr = $this->getUser();
        $groupe=null;
        $classe= $this->getDoctrine()->getEntityManager()->getRepository('GestionAbsenceBundle:Classe')->findAll();
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $etudiant = new Etudiant();
        //on recupere le formulaire
        $form = $this->createForm(EtudiantType::class,$etudiant);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            //var_dump($etat);die('HELLO');
            $username = $donnee->getLogin();
            //var_dump($username);die('Hello');
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $roles = 'ROLE_ETUDIANT';

            //var_dump($username,$password,$email,$roles);die('Hello');
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->createUser();
            if ($etat=='Inactif') {
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(false);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
            else{
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(true);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
                }
            //var_dump(array($donnee->getClasse()));die('Hello');

            $em->persist($etudiant);
            $em->flush();
            return $this->redirect($this->generateUrl('listEtudiant',array('user' => $usr)));
        }
        //on rend la vue
        return $this->render('GestionPreinscriptionBundle:Default:ajouterEtudiant.html.twig',array('user' => $usr,
            'form'   => $formView, 'classe'   => $classe, 'groupe'   => $groupe));
    }

    public function verifLoginAction(Request $request)
    {
        $pseudo = $request->query->get('pseudo');
        //var_dump($pseudo,$email);die('Hello');
        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        // Pour récupérer la liste de tous les utilisateurs
        $pseuudo =$userManager->findUserByUsername($pseudo);
        //var_dump($username);die('Hello');
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
            echo "<span style=\"color:#00d95a;\">L'email <span style=\"color:#0000C0;\">$email</span> Disponible.</span></span>";
        }
        exit();
    }

    public function modifierEtudiantAction($id, Request $request)
    {
        $usr = $this->getUser();
        $Old_user=$request->get('username');
        $Old_usr=$request->get('idEtud');
        $oldPwd=$request->get('oldPwd');
        //var_dump($Old_user);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $em->getRepository('GestionPreinscriptionBundle:Etudiant')->find($id);
        if (null === $etudiant) {
            throw new NotFoundHttpException("L'étudiant d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(EtudiantType::class, $etudiant);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            $username = $donnee->getLogin();
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $roles = 'ROLE_ETUDIANT';
            $dateNaisPerson=$donnee->getDateNaissance();
            $dateNaisPers=$request->get('dateNaisEtud');
            $dateObtDip=$donnee->getAnneeObtention();
            $dateObtnDip=$request->get('dateObtDip');

            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($Old_usr);

            if ($etat=='Inactif') {
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(false);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
            else {
                $user->setUsername($username);
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(true);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
            if (null === $dateNaisPerson || null === $dateObtDip) {
                $date = new \DateTime($dateNaisPers);
                $dateObt = new \DateTime($dateObtnDip);
                $etudiant->setDateNaissance($date);
                $etudiant->setAnneeObtention($dateObt);
            }
            $em->flush();
            return $this->redirectToRoute('listEtudiant', array('id' => $etudiant->getId(),'user' => $usr ));
        }

        return $this->render('GestionPreinscriptionBundle:Default:modifierEtudiant.html.twig', array('user' => $usr,
            'etudiant' => $etudiant,
            'form' => $formView, 'oldUser' => $Old_user
        ));
    }

    public function EditProfilEtudiantAction($id, Request $request)
    {
        $Classe=$request->get('classe');
        $Groupe=$request->get('groupe');
        $usr = $this->getUser();
        $Old_user=$request->get('username');
        $Old_usr=$request->get('idEtud');
        $oldPwd=$request->get('oldPwd');
        //var_dump($Old_user);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $em->getRepository('GestionPreinscriptionBundle:Etudiant')->find($id);
        if (null === $etudiant) {
            throw new NotFoundHttpException("L'étudiant d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(EtudiantEditType::class, $etudiant);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            $username = $donnee->getLogin();
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $roles = 'ROLE_ETUDIANT';
            $dateNaisPerson=$donnee->getDateNaissance();
            $dateNaisPers=$request->get('dateNaisEtud');
            $dateObtDip=$donnee->getAnneeObtention();
            $dateObtnDip=$request->get('dateObtDip');
            //var_dump($username,$Old_usr,$roles,$password,$email,$roles,$etat);die('Hello');
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($usr);
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(false);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);

            if (null === $dateNaisPerson || null === $dateObtDip) {
                $date = new \DateTime($dateNaisPers);
                $dateObt = new \DateTime($dateObtnDip);
                $etudiant->setDateNaissance($date);
                $etudiant->setAnneeObtention($dateObt);
            }
            $em->flush();
            return $this->redirectToRoute('DashboardEtudiant', array('user' => $usr));
        }

        return $this->render('GestionPreinscriptionBundle:Default:modifierProfilEtudiant.html.twig', array('user' => $usr,
            'etudiant' => $etudiant, 'form' => $formView, 'oldUser' => $Old_user, 'classe' => $Classe, 'groupe' => $Groupe
        ));
    }

    public function supprimerEtudiantAction(Request $request, $id)
    {
        $username=$request->get('username');
        //var_dump($username);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Etudiant')->find($id);
        if (!$etudiant)
        {
            throw new NotFoundHttpException("Etudiant non trouvé");
        }

        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        $usr = $userManager->findUserByUsername($username);
        $em->remove($usr);

        $em->remove($etudiant);
        $em->flush();
        $usrs = $this->getUser();
        return new RedirectResponse($this->container->get('router')->generate('listEtudiant',array('user' => $usrs)));
    }


    public function infosEtudiantAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $em->getRepository('GestionPreinscriptionBundle:Etudiant')->find($id);
        $usr = $this->getUser();
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:infosEtudiant.html.twig',
            array(
                'etudiant' => $etudiant, 'user' => $usr
            ));
    }

    public function infosPreEtudiantAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrit= $em->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);
        $usr = $this->getUser();
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:infosPreEtudiant.html.twig',
            array(
                'preinscrit' => $preinscrit, 'user' => $usr
            ));
    }

    public function contactAction(Request $request)
    {
        $usr = $this->getUser();
        $form = $this->createForm('Gestion\PreinscriptionBundle\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('myapplication_contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :

                    return $this->redirectToRoute('email_valide',array('user' => $usr));
                }else{
                    // An error ocurred, handle
                    var_dump("Errooooor :(");
                }
            }
        }

        return $this->render('GestionPreinscriptionBundle:Default:contact.html.twig', array('user' => $usr,
            'form' => $form->createView()
        ));
    }

    private function sendEmail($data)
    {
        $myappContactMail = 'jeap37208@gmail.com';
        $myappContactPassword = 'bigpassword';

        // In this case we'll use the gmail mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("A Propos de votre Preinscription ". $data["subject"])
            ->setFrom(array($myappContactMail => "Message envoyé par ".$data["nom"] ." ".$data["prenom"]))
            ->setTo(array(
                $myappContactMail => $myappContactMail
            ))
            ->setBody($data["message"]." "."ContactMail :".$data["email"]);

        return $mailer->send($message);
    }

    public function validerMailAction()
    {
        $usr = $this->getUser();
        return $this->render('GestionPreinscriptionBundle:Default:reponse.html.twig', array(
            'user' => $usr));
    }


    public function listParentAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $parent = $em->getRepository('GestionPreinscriptionBundle:Parents')->findAll();
        $usr = $this->getUser();
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:listParents.html.twig', array(
                'parent' => $parent, 'user' => $usr)
        );

    }

    public function infosParentAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $parent= $em->getRepository('GestionPreinscriptionBundle:Parents')->find($id);
        $usr = $this->getUser();
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:infosParents.html.twig',
            array(
                'parent' => $parent, 'user' => $usr
            ));
    }

    public function ajouterParentAction(Request $request)
    {
        $usrs = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $parent = new Parents();
        //on recupere le formulaire
        $form = $this->createForm(ParentsType::class,$parent);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            $username = $donnee->getLogin();
            //var_dump($username);die('Hello');
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $roles = 'ROLE_PARENT';

            //var_dump($username,$password,$email,$roles);die('Hello');
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->createUser();
            if ($etat=='Inactif') {
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(false);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
            else{
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(true);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
            $em->persist($parent);
            $em->flush();
            return $this->redirect($this->generateUrl('listParent',array('user' => $usrs)));
        }
        //on rend la vue
        return $this->render('GestionPreinscriptionBundle:Default:ajouterParent.html.twig',array('user' => $usrs,
            'form'   => $formView));
    }


    public function modifierParentAction($id, Request $request)
    {
        $usr = $this->getUser();
        $Old_user=$request->get('username');
        $Old_usr=$request->get('idEtud');
        $oldPwd=$request->get('oldPwd');
        //var_dump($Old_user);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $parent= $em->getRepository('GestionPreinscriptionBundle:Parents')->find($id);
        if (null === $parent) {
            throw new NotFoundHttpException("Le parent d'id ".$id." n'existe pas.");
        }
        //on recupere le formulaire
        $form = $this->createForm(ParentsType::class, $parent);
        //on génère le html du formulaire crée
        $formView = $form->createView();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $donnee = $form->getData();
            $etat = $donnee->getEtat();
            $username = $donnee->getLogin();
            $password = $donnee->getPassword();
            $email = $donnee->getEmail();
            $roles = 'ROLE_PARENTS';
            $dateNaisPerson=$donnee->getDateNaissance();
            $dateNaisPers=$request->get('dateNaisParnt');
            //var_dump($username,$Old_usr,$password,$email,$roles);die('Hello');
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($Old_usr);
            if ($etat=='Inactif') {
                $user->setUsername($username);
                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 13]) ;
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(false);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
            else {
                $user->setUsername($username);
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
                $user->setPassword($hash);
                $user->setEmail($email);
                $user->setEnabled(true);
                $user->setRoles(array($roles));
                $userManager->updateUser($user);
            }
                // Inutile de persister ici, Doctrine connait déjà les donées
            if (null === $dateNaisPerson) {
                $date = new \DateTime($dateNaisPers);
                $parent->setDateNaissance($date);
            }
            $em->flush();
            return $this->redirectToRoute('listParent', array('id' => $parent->getId(), 'user' => $usr));
        }

        return $this->render('GestionPreinscriptionBundle:Default:modifierParent.html.twig', array( 'user' => $usr,
            'parent' => $parent, 'form' => $formView, 'oldUser' => $Old_user,
        ));
    }

    public function supprimerParentAction(Request $request, $id)
    {
        $username=$request->get('username');
        //var_dump($username);die('Hello');
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Parents')->find($id);
        if (!$etudiant)
        {
            throw new NotFoundHttpException("Parent non trouvé");
        }

        //ajout des paramètres username et password dans la table 'fos_user'
        $userManager = $this->get('fos_user.user_manager');
        $usr = $userManager->findUserByUsername($username);
        $em->remove($usr);

        $em->remove($etudiant);
        $em->flush();
        $usrs = $this->getUser();
        return new RedirectResponse($this->get('router')->generate('listParent',array('user' => $usrs)));
    }
}
