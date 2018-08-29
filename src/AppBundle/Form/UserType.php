<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username')
        // Voir la doc pour créer une méthode ou un subscriber pour une meilleure lisibilité
        // cf : http://symfony.com/doc/3.4/form/events.html
        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $userData = $event->getData();
            $userForm = $event->getForm();

            // Nouveau User ?
            dump($userData->getId());
            if ($userData->getId()) {
                $userForm->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'first_options' => array(
                        'label' => 'Password',
                        'attr' => array('placeholder' => 'Laisser vide si inchangé')
                    ),
                    'second_options' => array(
                        'label' => 'Vérification du mot de passe',
                        'attr' => array('placeholder' => 'Laisser vide si inchangé')
                    ),
                ));
            } else {
                // User existant
                $userForm->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'constraints' => array( new NotBlank() ),
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Vérification du mot de passe'),
                ));
            }
        })
        ->add('email')
        ->add('isActive')
        ->add('role');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'attr' => ['novalidate' => 'novalidate']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
