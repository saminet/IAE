<?php

namespace Gestion\PaiementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementEnseignantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dateTranche1')->add('dateTranche2')->add('dateTranche3')->add('EtatDateTranche1')->add('EtatDateTranche2')->add('EtatDateTranche3')->add('enseignant')        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gestion\PaiementBundle\Entity\PaiementEnseignant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestion_paiementbundle_paiementenseignant';
    }


}
