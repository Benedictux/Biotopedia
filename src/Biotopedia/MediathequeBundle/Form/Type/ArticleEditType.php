<?php
//src/Biotopedia/MediathequeBundle/Form/Type/ArticleEditType.php
namespace Biotopedia\MediathequeBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleEditType extends ArticleType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      // On fait appel à la méthode buildForm du parent, qui va ajouter tous les champs à $builder
      parent::buildForm($builder, $options);
      
      // On rajoute ou supprime les champs souhaités dans le formulaire de modification
      //'->remove' ou '->add'.
      $builder;
    }

    public function getName()
    {
        return 'articleEditType';
    }
}