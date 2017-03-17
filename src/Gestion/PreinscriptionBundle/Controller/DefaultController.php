<?php

namespace Gestion\PreinscriptionBundle\Controller;
use Gestion\PreinscriptionBundle\Entity\Preinscrit;
use Gestion\PreinscriptionBundle\Entity\Etudiant;
use Gestion\PreinscriptionBundle\Entity\Task;
use Gestion\PreinscriptionBundle\Form\EtudiantForm;
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


 public function listAction() {
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
        $etudiant->setMessage($preinscrit->getMessage());
        $etudiant->setNiveau($preinscrit->getNiveau());
        $etudiant->setFormation($preinscrit->getFormation());

        $em->persist($etudiant);

        $em->flush();

        return $this->render('GestionPreinscriptionBundle:Default:valider_preinscrit.html.twig',array(
            'etudiant' => $etudiant) );
    }

    public function listEtudiantAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $etudiants = $em->getRepository('GestionPreinscriptionBundle:Etudiant')->findAll();

        return $this->container->get('templating')->renderResponse('GestionAdminBundle:Default:listeEtudiant.html.twig',array(
                'etudiants' => $etudiants)
        );
    }

    public function refuserPreInscritAction($id)
    {
        return $this->container->get('templating')->renderResponse(
            'GestionPreinscriptionBundle:Default:refuser.html.twig');
    }

    public function ajouterEtudiantAction()
    {
        $etudiant = new Etudiant();
        $form = $this->container->get('form.factory')->create(new EtudiantForm(), $etudiant);

        return $this->container->get('templating')->renderResponse(
            'GestionPreinscriptionBundle:Default:ajouterEtudiant.html.twig',
            array(
                'form' => $form->createView(),
                'message' => ''
            ));
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

                    return $this->redirectToRoute('GestionPreinscriptionBundle:Default:reponse.html.twig');
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

        $message = \Swift_Message::newInstance("Our Code World Contact Form ". $data["subject"])
            ->setFrom(array($myappContactMail => "Message by ".$data["name"]))
            ->setTo(array(
                $myappContactMail => $myappContactMail
            ))
            ->setBody($data["message"]."<br>ContactMail :".$data["email"]);

        return $mailer->send($message);
    }


    public function newAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        return $this->render('GestionPreinscriptionBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
