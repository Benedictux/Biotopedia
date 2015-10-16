<?php
//src/Biotopedia/UsersBundle/Form/Type/UserPWType.php
namespace Biotopedia\UsersBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class UserPWType extends UserType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    // On fait appel à la méthode buildForm du parent, qui va ajouter tous les champs à $builder
    parent::buildForm($builder, $options);
    
    $builder
      ->remove('username')
      ->remove('email')
      ->remove('conditionsdutilisations')
      ;
  }

  public function getName()
  {
    return 'userPWType';
  }
}