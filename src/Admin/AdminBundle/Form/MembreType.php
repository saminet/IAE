<?php

namespace Admin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MembreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array('attr' => array('placeholder'=>'Nom de l\'étudiant','class'=>'form-control')))
            ->add('prenom',TextType::class, array('attr' => array('placeholder'=>'Prénom de l\'étudiant','class'=>'form-control')))
            ->add('dateNaissance',DateType::class, array('required' => true,
                // render as a single text box
                'widget' => 'single_text',
                'input' => 'datetime'))
            ->add('sexe',ChoiceType::class, array('placeholder'=>'Choisir le sexe','choices' => array('Masculin'=>'Masculin','Féminin'=>'Féminin'),
                'expanded' => true,
                'multiple' => false))
            ->add('rib',TextType::class, array('attr' => array('placeholder'=>'Nom de l\'étudiant','class'=>'form-control')))
            ->add('adresse',TextareaType::class, array('attr' => array('placeholder'=>'Adresse de l\'enseignant','class'=>'form-control')))
            ->add('lieuxNaissance',TextType::class, array('attr' => array('placeholder'=>'Lieu de Naissance de l\'enseignant','class'=>'form-control')))
            ->add('nationalite',TextType::class, array('attr' => array('placeholder'=>'Nationalité de l\'enseignant','class'=>'form-control')))
            ->add('ville',TextType::class, array('attr' => array('placeholder'=>'Ville de l\'enseignant','class'=>'form-control')))
            ->add('cin',IntegerType::class, array('attr' => array('placeholder'=>'Numéro CIN/Passeport','class'=>'form-control')))
            ->add('tel',IntegerType::class, array('attr' => array('placeholder'=>'Téléphone de l\'enseignant','class'=>'form-control')))
            ->add('email',EmailType::class, array('attr' => array('placeholder'=>'Email de l\'enseignant','class'=>'form-control')))
            ->add('role', EntityType::class, array(
                'required' => true,
                'class' => 'AdminAdminBundle:Profil',
                'placeholder' => '-- Choisir la profil --',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.posteProfil', 'ASC');
                },
                'choice_label' => 'nomProfil',
                'attr' => array(
                    'class'     => 'form-control',
                    'property' => "libCategory", 'multiple' => false, 'expanded' => true
                ),
            ))

            ->add('login',TextType::class, array('attr' => array('placeholder'=>'choisir un nom d\'utilisateur','class'=>'form-control')))
            ->add('password',PasswordType::class, array('attr' => array('placeholder'=>'Choisir un mot de Passe','class'=>'form-control')))
            ->add('etat',ChoiceType::class, array('placeholder'=>'Choisir son état','choices' => array('Actif'=>'Actif', 'Inactif'=>'Inactif'),
                'expanded' => true,
                'multiple' => false))
            ->add('submit',SubmitType::class, array('attr' => array('class'=>'btn btn-success')))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AdminBundle\Entity\Membre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_adminbundle_membre';
    }


}
