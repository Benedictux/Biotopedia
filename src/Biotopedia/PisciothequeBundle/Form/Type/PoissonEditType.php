<?php
//src/Biotopedia/PisciothequeBundle/Form/Type/PoissonEditType.php
namespace Biotopedia\PisciothequeBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;
use Biotopedia\CoreBundle\Form\Type\SourceType;

class PoissonEditType extends PoissonFamilleType
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
      ->remove('image')
      ->add('image', new ImageType(), array('required' => false));
    }

  public function getName()
  {
    return 'poissonEditType';
  }
}