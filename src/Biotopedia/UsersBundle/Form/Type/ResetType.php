<?php
//src/Biotopedia/UsersBundle/Form/Type/ResetType.php
namespace Biotopedia\UsersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ResetType extends AbstractType
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
      ->add('username', 'text')
      ;
  }

  public function getName()
  {
    return 'resetType';
  }
}