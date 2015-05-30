<?php
//src/Biotopedia/MediathequeBundle/Form/Type/ArticleType.php
namespace Biotopedia\MediathequeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
 //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Biotopedia\MediathequeBundle\Entity\Article'
    ));
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('titre', 'text')
      ->add('contenu', 'ckeditor', array(
        'config_name' => 'default',
        'config' => array(
          //'toolbar' => array(
            //array(
              //'name'  => 'document',
              //'items' => array('Source', '-', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates'),
            //),
            //'/',// le / permet de sauter une ligne entre deux toolbar
            //array(
              //'name'  => 'basicstyles',
              //'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat', '-', 'Link'),
            //),
          //),
        //'uiColor' => '#252525',
        //'filebrowser_image_browse_url' => array(
              //'route'            => 'elfinder',
              //'route_parameters' => array('instance' => 'ckeditor')),
        //...
        ),
      ))
      ->add('publie', 'checkbox', array(
        'label'     => 'Publier cet article sur le site maintenant ?',
        'required'  => false,
));
  }

  public function getName()
  {
    return 'articleType';
  }
}