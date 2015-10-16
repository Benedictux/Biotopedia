<?php
//src/Biotopedia/UsersBundle/Form/Type/UserAdminType.php
namespace Biotopedia\UsersBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserAdminType extends UserType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    // On fait appel à la méthode buildForm du parent, qui va ajouter tous les champs à $builder
    parent::buildForm($builder, $options);

    $builder
      ->remove ('password')
      ->remove('conditionsdutilisations')
      ;
  }

  public function getName()
  {
    return 'userAdminType';
  }
}