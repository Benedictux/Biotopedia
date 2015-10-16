<?php
//src/Biotopedia/UserBundle/Form/Type/UserEditAdminType.php
namespace Biotopedia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditAdminType extends AbstractType
{
  //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Biotopedia\UserBundle\Entity\User'
    ));
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $builder
      ->add('username', 'text')
      ->add('email', 'email')
      //->add('roles', 'choice', array(
        //'choices' => array(
          //'ROLE_USER' =>'ROLE_USER',
          //'ROLE_AUTEUR' =>'ROLE_AUTEUR',
          //'ROLE_ADMIN' =>'ROLE_ADMIN',
          //'ROLE_SUPER_ADMIN' =>'ROLE_SUPER_ADMIN')))
      ->add('enabled','checkbox', array(
          'required'  => false)); 
  }

  public function getName()
  {
    return 'userEditAdminType';
  }
}