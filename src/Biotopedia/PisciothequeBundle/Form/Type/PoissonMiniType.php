<?php
//src/Biotopedia/PisciothequeBundle/Form/Type/PoissonMiniType.php
namespace Biotopedia\PisciothequeBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class PoissonMiniType extends PoissonFamilleType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
  */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    // On fait appel à la méthode buildForm du parent, qui va ajouter tous les champs à $builder
    parent::buildForm($builder, $options);
    $builder
      ->remove('sources')
      ->remove('origine')
      ->remove('zone')
      ->remove('difficulte')
      ->remove('taille')
      ->remove('temperature')
      ->remove('ph')
      ->remove('durete')
      ->remove('dimorphisme_sexuel')
      ->remove('comportement_social')
      ->remove('reproduction');
    }

  public function getName()
  {
    return 'poissonMiniType';
  }
}