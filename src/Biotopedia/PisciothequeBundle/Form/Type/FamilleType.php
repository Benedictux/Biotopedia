<?php
//src/Biotopedia/PisciothequeBundle/Form/Type/FamilleType.php
namespace Biotopedia\PisciothequeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class FamilleType extends AbstractType
{
 //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Biotopedia\PisciothequeBundle\Entity\Famille'
    ));
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('common_name', 'text')
      ->add('scientific_name', 'text')
      ->add('description','textarea')
      ->add('image', new ImageType())
      /*
      * Rappel :
       ** - 1er argument : nom du champ, ici « poissons », car c'est le nom de l'attribut
       ** - 2e argument : type du champ, ici « collection » qui est une liste de quelque chose
       ** - 3e argument : tableau d'options du champ
       ** allow_add et allow_delete, autorisent au formulaire l'ajout d'entrées en plus dans la collection, ainsi que d'en supprimer
       ** Normalement, le framework de formulaire modifierait les poissons d'un objet Famille sans 
       ** jamais appeler setPoissons. En définissant by_reference à false, setPoissons sera appelé
      */
      ->add('poissons', 'collection', array(
        'type'         => new PoissonFamilleType(),
        'allow_add'    => true,
        'allow_delete' => true,
        'by_reference' => false
      )); 
  }

  public function getName()
  {
    return 'familleType';
  }
}