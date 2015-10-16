<?php
//src/Biotopedia/UsersBundle/Form/Type/UserType.php
namespace Biotopedia\UsersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class UserType extends AbstractType
{
 //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Biotopedia\UsersBundle\Entity\User'
    ));
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username', 'text', array('required' => true))
      ->add('password', 'repeated', array(
        'type' => 'password',
        'invalid_message' => 'Les mots de passe doivent correspondre',
        'options' => array('required' => true),
        'first_options'  => array('label' => 'Mot de passe'),
        'second_options' => array('label' => 'Mot de passe (validation)')))
      ->add('email', 'repeated', array(
        'type' => 'email',
        'invalid_message' => 'Les emails doivent correspondre',
        'options' => array('required' => true),
        'first_options'  => array('label' => 'email'),
        'second_options' => array('label' => 'email (validation)')))
      ->add('conditionsdutilisations', 'checkbox', array('mapped' => false))
      ;
  }

  public function getName()
  {
    return 'userType';
  }
}