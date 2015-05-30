<?php
//src/Biotopedia/MediathequeBundle/Form/Type/CategorieType.php
namespace Biotopedia\MediathequeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class CategorieType extends AbstractType
{
 //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Biotopedia\MediathequeBundle\Entity\Categorie'
    ));
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', 'text')
      ->add('description','textarea')
      ->add('image', new ImageType());
  }

  public function getName()
  {
    return 'categorieType';
  }
}