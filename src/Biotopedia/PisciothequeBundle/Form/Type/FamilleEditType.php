<?php
//src/Biotopedia/PisciothequeBundle/Form/Type/FamilleEditType.php
namespace Biotopedia\PisciothequeBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class FamilleEditType extends FamilleType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      // On fait appel à la méthode buildForm du parent, qui va ajouter tous les champs à $builder
      parent::buildForm($builder, $options);
      
      // On rajoute ou supprime les champs souhaités dans le formulaire de modification
      //'->remove' ou '->add'.
      $builder
      ->remove('poissons')
      ->remove('image')
      ->add('image', new ImageType(), array('required' => false));
      //->add('image', 'entity', array(
        //    'class'    => 'BiotopediaPisciothequeBundle:Image',
        //    'property' => 'path',
        //    'multiple' => false,
        //    'expanded' => true,
        //    'required' => false));
    }

    public function getName()
    {
        return 'familleEditType';
    }
}