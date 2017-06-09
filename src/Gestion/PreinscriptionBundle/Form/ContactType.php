<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 16/03/2017
 * Time: 16:39
 */

namespace Gestion\PreinscriptionBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('attr' => array('placeholder' => ''),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez introduire votre nom")),
                )
            ))

            ->add('prenom', TextType::class, array('attr' => array('placeholder' => ''),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez introduire votre prenom")),
                )
            ))

            ->add('subject', TextType::class, array('attr' => array('placeholder' => ''),
                'constraints' => array(
                    new NotBlank(array("message" => "Votre Sujet")),
                )
            ))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Votre adrese Email'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez indiquer une adresse mail valide")),
                    new Email(array("message" => "Votre Email semble invalide")),
                )
            ))
            ->add('message', TextareaType::class, array('attr' => array('placeholder' => 'Tapez votre message ici'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez indiquer votre message")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}