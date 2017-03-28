<?php

namespace Gestion\PreinscriptionBundle\Controller;
use Gestion\PreinscriptionBundle\Entity\Preinscrit;
use Gestion\PreinscriptionBundle\Entity\Etudiant;
use Gestion\PreinscriptionBundle\Entity\Task;
use Gestion\PreinscriptionBundle\Form\EtudiantForm;
use Gestion\PreinscriptionBundle\Form\EtudiantType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('GestionPreinscriptionBundle:Default:index.html.twig');
    }


 public function newAction() {
        $em = $this->container->get('doctrine')->getEntityManager();

        $preinscrit1 = new Preinscrit();
        $preinscrit1->setNom('Simone');
        $preinscrit1->setPrenom('Crockett');
        $preinscrit1->setDateNaissance(new \DateTime("2011-07-23 06:12:33"));
        $preinscrit1->setLieuNaissance('Paris');
        $preinscrit1->setNationalite('Francaise');
        $preinscrit1->setVille('Paris');
        $preinscrit1->setNumCinPass('123990');
        $preinscrit1->setSexe('Masculain');
        $preinscrit1->setAdresse('Paris');
        $preinscrit1->setTel('123456');
        $preinscrit1->setEmail('Alain@gmail.com');
        $preinscrit1->setDiplome('Metrise');
        $preinscrit1->setEtablissement('Sorbon');
        $preinscrit1->setAnneeObtention(new \DateTime("2011-07-23 06:12:33"));
        $preinscrit1->setMessage('Bonjour');
        $preinscrit1->setNiveau('Master');
        $preinscrit1->setFormation('Finance');
        $em->persist($preinscrit1);

        $em->flush();

        $message = 'Insertion terminée : ';

        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:index.html.twig',
            array('message' => $message)
        );
    }

    public function listPreinscritAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrits = $em->getRepository('GestionPreinscriptionBundle:Preinscrit')->findAll();

        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:listePre.html.twig',array(
                'preinscrits' => $preinscrits)
        );
    }


    public function accepterPreinscritAction($id)
    {
        $preinscrit= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);

        $attachment = \Swift_Attachment::fromPath('../web/uploads/frais-scolarite.pdf');
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Préinscription IAE')
            ->setFrom('jeap37208@gmail.com')
            ->setTo(''.$preinscrit->getEmail().'')
            ->setBody(
                'Bonjour Mr/Mme '.$preinscrit->getPrenom().',<br /> <br /> <br /> nous vous informons que votre demande d\'inscription à été bien accéptée 
                <br /><br />  Vous trouvez ci dessous toutes les informations pour compléter votre dossier ainsi que les frais de scolarités.<br /><br /> <br />Si vous avez quelque chose 
                à renseigner, merci de nous avoir contacter sur :<br /><br />Email :  iaetunis@gmail.com / contact@iaetunis.com <br /><br />Tél. : 94569697<br /><br />Fax : 71845269 E-mail <br /> <br /> <br /> <br /> <br /> <br />
                Cordialement','text/html'
            )
            ->attach($attachment);

        $type = $message->getHeaders()->get('Content-Type');
        $type->setParameter('charset', 'utf-8');

        $this->get('mailer')->send($message);


        return $this->render('GestionPreinscriptionBundle:Default:accepterPreinscrit.html.twig',array(
            'candidat' => $preinscrit) );
    }


    public function refuserPreinscritAction($id)
    {
        $preinscrit= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);

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


        return $this->render('GestionPreinscriptionBundle:Default:refuserPreinscrit.html.twig',array(
            'candidat' => $preinscrit) );
    }


    public function suprimerPreinscritAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrit= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);
        if (!$preinscrit)
        {
            throw new NotFoundHttpException("Aucune préinscrit trouvé");
        }
        $em->remove($preinscrit);
        $em->flush();

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

        return new RedirectResponse($this->container->get('router')->generate('Liste_preinscrits'));
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
        $etudiant->setNiveau($preinscrit->getNiveau());
        $etudiant->setFormation($preinscrit->getFormation());

        $em->persist($etudiant);

        $em->flush();

        $attachment = \Swift_Attachment::fromPath('../web/uploads/frais-scolarite.pdf');
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Préinscription IAE')
            ->setFrom('jeap37208@gmail.com')
            ->setTo(''.$preinscrit->getEmail().'')
            ->setBody(
                'Bonjour Mr/Mme '.$preinscrit->getPrenom().',<br /> <br /> <br /> nous vous informons que votre demande d\'inscription à été bien accéptée 
                <br /><br />  Vous trouvez ci dessous toutes les informations pour compléter votre dossier ainsi que les frais de scolarités.<br /><br /> <br />Si vous avez quelque chose 
                à renseigner, merci de nous avoir contacter sur :<br />Email :  iaetunis@gmail.com / contact@iaetunis.com <br /><br />Tél. : 94569697<br /><br />Fax : 71845269 E-mail <br /> <br /> <br /> <br /> <br /> <br />
                Cordialement','text/html'
            )
           ->attach($attachment);

        $type = $message->getHeaders()->get('Content-Type');
        $type->setParameter('charset', 'utf-8');

        $this->get('mailer')->send($message);


        return $this->render('GestionPreinscriptionBundle:Default:valider_preinscrit.html.twig',array(
            'etudiant' => $etudiant) );
    }

    public function listEtudiantAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiants = $em->getRepository('GestionPreinscriptionBundle:Etudiant')->findAll();

        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:listEtd.html.twig',array(
                'etudiants' => $etudiants)
        );
    }

    public function ajouterEtudiantAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $etudiant = new Etudiant();
        //on recupere le formulaire
        $form = $this->createForm(EtudiantType::class,$etudiant);

        //on génère le html du formulaire crée
        $formView = $form->createView();
        // Refill the fields in case the form is not valid.
        $form->handleRequest($request);

        if($form->isSubmitted()){
           if($form->isValid()){
               $em->persist($etudiant);
               $em->flush();
               return $this->redirect($this->generateUrl('listEtudiant'));
           }
        }
        //on rend la vue
        return $this->render('GestionPreinscriptionBundle:Default:ajouterEtudiant.html.twig',array(
            'form' => $formView) );
    }

    public function modifierEtudiantAction($id, Request $request)
    {
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
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Les données de l\'étudiant'.$etudiant->getId().' est bien modifiée.');

            return $this->redirectToRoute('listEtudiant', array('id' => $etudiant->getId()));
        }

        return $this->render('GestionPreinscriptionBundle:Default:modifierEtudiant.html.twig', array(
            'etudiant' => $etudiant,
            'form'   => $formView,
        ));
    }

    public function sauvegarderEtudiantAction(Request $request,$id)
    {
        $idEtd =  $request->get('idEtud');
        dump($idEtd);
        die('Hello !!');
        $em = $this->container->get('doctrine')->getEntityManager();
        //on crée un nouveau etudiant
        $etudiant = new Etudiant();

        //on recupere le formulaire
        $form = $this->createForm(EtudiantType::class,$etudiant);

        //on génère le html du formulaire crée
        $formView = $form->createView();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em->persist($etudiant);
                $em->flush();
                return $this->redirect($this->generateUrl('listEtudiant'));
            }
        }
        //on rend la vue
        return $this->render('GestionPreinscriptionBundle:Default:modifierEtudiant.html.twig',array(
            'form' => $formView) );
    }


    public function contactAction(Request $request)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
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

                    return $this->redirectToRoute('email_valide');
                }else{
                    // An error ocurred, handle
                    var_dump("Errooooor :(");
                }
            }
        }

        return $this->render('GestionPreinscriptionBundle:Default:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function sendEmail($data){
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
            ->setFrom(array($myappContactMail => "Message envoyé par ".$data["name"]))
            ->setTo(array(
                $myappContactMail => $myappContactMail
            ))
            ->setBody($data["message"]." "."ContactMail :".$data["email"]);

        return $mailer->send($message);
    }

    public function supprimerEtudiantAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $this->getDoctrine()->getRepository('GestionPreinscriptionBundle:Etudiant')->find($id);
        if (!$etudiant)
        {
            throw new NotFoundHttpException("Etudiant non trouvé");
        }
        $em->remove($etudiant);
        $em->flush();
        return new RedirectResponse($this->container->get('router')->generate('listEtudiant'));
    }


    public function infosEtudiantAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiant= $em->getRepository('GestionPreinscriptionBundle:Etudiant')->find($id);
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:infosEtudiant.html.twig',
        array(
            'etudiant' => $etudiant
        ));
    }

    public function infosPreEtudiantAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $preinscrit= $em->getRepository('GestionPreinscriptionBundle:Preinscrit')->find($id);
        return $this->container->get('templating')->renderResponse('GestionPreinscriptionBundle:Default:infosPreEtudiant.html.twig',
            array(
                'preinscrit' => $preinscrit
            ));
    }


        



















}
