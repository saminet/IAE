<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 16/03/2017
 * Time: 12:01
 */

namespace Gestion\PreinscriptionBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class EtudiantForm extends AbstractType
{
    public function buildForm(FormBuilderInterface  $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance', 'birthday')
            ->add('sexe', 'choice', array('choices' => array('F'=>'Féminin','M'=>'Masculin')))
            ->add('age')
            ->add('adresse')
            ->add('lieuNaissance')
            ->add('ville')
            ->add('numCinPass')
            ->add('tel')
            ->add('email')
            ->add('diplome')
            ->add('etablissement')
            ->add('anneeObtention')
            ->add('message')
            ->add('niveau')
            ->add('formation')
            ->add('nationalite')
            ->add('diplome')
        ;
    }

    public function getName()
    {
        return 'etudiant';
    }
}