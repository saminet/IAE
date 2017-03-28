<?php

namespace Gestion\NiveauBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class NiveauType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomNiveau',TextType::class, array('attr' => array('class'=>'form-control')))
           // ->add('nomFiliere',TextType::class, array('attr' => array('class'=>'form-control')))
           // ->add('nomFiliere','entity', array('class'=>'Gestion\FiliereBundle\Entity\Filiere', 'property'=>'nomFiliere', ))
           // ->add('nomFiliere', 'entity', array('label' => 'nomFiliere','class' => 'GestionFiliereBundle:Filiere','expanded' => false,'multiple' => false))
            ->add('submit',SubmitType::class, array('attr' => array('class'=>'btn btn-success')));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gestion\NiveauBundle\Entity\Niveau'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestion_niveaubundle_niveau';
    }


}
