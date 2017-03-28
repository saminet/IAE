<?php

namespace Gestion\PreinscriptionBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface  $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('prenom',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('dateNaissance',DateType::class, array('attr' => array('class'=>'form-control')))
            ->add('sexe',ChoiceType::class, array('choices' => array('Féminin'=>'Féminin','Masculin'=>'Masculin'),'attr' => array('class'=>'form-control')))
            ->add('adresse',TextareaType::class, array('attr' => array('class'=>'form-control')))
            ->add('lieuNaissance',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('ville',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('numCinPass',IntegerType::class, array('attr' => array('class'=>'form-control')))
            ->add('tel',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('email',EmailType::class, array('attr' => array('class'=>'form-control')))
            ->add('diplome',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('etablissement',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('anneeObtention',DateType::class, array('attr' => array('class'=>'form-control')))
            ->add('niveau',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('formation',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('nationalite',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('diplome',TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('submit',SubmitType::class, array('attr' => array('class'=>'btn btn-success')))
        ;
    }

    public function getName()
    {
        return 'etudiant';
    }

}
