<?php

namespace Biotopedia\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
    //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Biotopedia\UserBundle\Entity\User'
            ));
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Ajoutez vos champs ici, revoilà notre champ *location* :
        //$builder->add('location');
        $builder
        ->add('conditionsUtilisation','checkbox', array(
            'required'  => true
            'label' => 'CU'
        ));
    }

    public function getName()
    {
        return 'biotopedia_user_registration';
    }
}