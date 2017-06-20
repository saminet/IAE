<?php

namespace Gestion\UEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Gestion\UEBundle\Form\TagsType as TagType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Gestion\AbsenceBundle\Entity\Classe;
use Gestion\MatiereBundle\Entity\Matiere;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UEType extends AbstractType
{
    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule',TextType::class, array('attr' => array('class'=>'form-control')))
            //->add('coeffUnite',integerType::class, array('attr' => array('class'=>'form-control')))
            //->add('creditUnite',integerType::class, array('attr' => array('class'=>'form-control')))

            ->add('classe', EntityType::class, array(
                'required' => true,
                'class' => 'GestionAbsenceBundle:Classe',
                'placeholder' => '-- Choisir la Classe --',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.intitule', 'ASC');
                },
                'choice_label' => 'intitule',
                'attr' => array(
                    'class'     => 'form-control',
                ),
            ))
            ->add('matieres', EntityType::class, array(
                'required' => true,
                'class' => 'GestionMatiereBundle:Matiere',
                'placeholder' => '',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nomMatiere', 'ASC');
                },
                'choice_label' => 'nomMatiere',
                'attr' => array(
                    'class'     => 'form-control',
                ),
            ))

            ->add('submit',SubmitType::class, array('attr' => array('class'=>'btn btn-success')));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gestion\UEBundle\Entity\UE'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestion_uebundle_ue';
    }
}
