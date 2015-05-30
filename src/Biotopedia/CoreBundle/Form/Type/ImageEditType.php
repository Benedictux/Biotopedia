<?php
//src/Biotopedia/CoreBundle/Form/Type/ImageEditType.php
namespace Biotopedia\CoreBundle\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Entity\Image;

class ImageEditType extends ImageType
{
    //Article mais c'est mieux de déclaré la class ratachée à ce formulaire
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Biotopedia\CoreBundle\Entity\Image',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      // On fait appel à la méthode buildForm du parent, qui va ajouter tous les champs à $builder
      parent::buildForm($builder, $options);
      
      // On rajoute ou supprime les champs souhaités dans le formulaire de modification
      //'->remove' ou '->add'.
      $builder
      ->add('image', 'entity', array(
            'class'    => 'BiotopediaCoreBundle:Image',
            'property' => 'path',
            'multiple' => false,
            'expanded' => false,
            'required' => false));
    }

    public function getName()
    {
        return 'imageEditType';
    }
}