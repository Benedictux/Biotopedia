<?php
//src/Biotopedia/PisciothequeBundle/Form/Type/PoissonFamilleType.php
namespace Biotopedia\PisciothequeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;
use Biotopedia\CoreBundle\Form\Type\SourceType;

class PoissonFamilleType extends AbstractType
{
  //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    //'cascade_validation' => true, active la validation sur FamilleType
      $resolver->setDefaults(array(
          'data_class' => 'Biotopedia\PisciothequeBundle\Entity\Poisson'
      ));
  }

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
  */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('common_name', 'text', array('label' => 'Nom commun', 'required' => false))
      ->add('scientific_name', 'text', array('label' => 'Nom scientifique'))
      ->add('origine', 'choice', array(
        'label' => 'Origine',
        'required' => false,
        'multiple'  => false,
        'expanded' => true,
        'choices'   => array(
          'Amérique du Nord' => 'Amérique du Nord',
          'Amérique Central' => 'Amérique Central',
          'Amérique du Sud' => 'Amérique du Sud',
          'Europe' => 'Europe',
          'Afrique' => 'Afrique',
          'Asie' => 'Asie')))
      ->add('type', 'choice', array(
        'label' => 'Milieu aquatique',
        'multiple'  => false,
        'expanded' => true,
        'choices'   => array(
          'Eau Douce' => 'Eau Douce',
          'Eau Saline' => 'Eau Saline',
          'Eau Douce En Bassin' => 'Eau Douce En Bassin')))
      ->add('zone', 'choice', array(
        'label' => 'Zone',
        'required' => false,
        'multiple'  => false,
        'expanded' => true,
        'choices'   => array(
          'Surface' => 'Surface',
          'Milieu' => 'Milieu',
          'Fond' => 'Fond')))
      ->add('difficulte', 'choice', array(
        'label' => 'Difficulté',
        'required' => false,
        'multiple'  => false,
        'expanded' => true,
        'choices'   => array(
          'Facile' => 'Facile',
          'Abordable' => 'Abordable',
          'Difficile' => 'Difficile')))
      ->add('taille', 'integer', array('label' => 'Taille Adulte (cm)', 'required' => false))
      ->add('temperature', 'number', array('label' => 'Température Optimal (C°)', 'precision' => 1, 'required' => false))
      ->add('ph', 'number', array('label' => 'Dureté Optimal (pH)', 'precision' => 1, 'required' => false))
      ->add('durete', 'number', array('label' => 'Taille Adulte (dH)', 'required' => false))
      ->add('image', new ImageType(), array('label' => 'Ajouter une photo'))
      ->add('description', 'textarea', array('label' => 'Déscription'))
      ->add('dimorphisme_sexuel', 'textarea', array('label' => 'Dimorphisme Sexuel', 'required' => false))
      ->add('comportement_social', 'textarea', array('label' => 'Comportement Social', 'required' => false))
      ->add('reproduction', 'textarea', array('label' => 'Reproduction', 'required' => false))
/*
* Rappel :
** - 1er argument : nom du champ, ici « poissons », car c'est le nom de l'attribut
** - 2e argument : type du champ, ici « collection » qui est une liste de quelque chose
** - 3e argument : tableau d'options du champ
** allow_add et allow_delete, autorisent au formulaire l'ajout d'entrées en plus dans la collection, ainsi que d'en supprimer
** Normalement, le framework de formulaire modifierait les poissons d'un objet Famille sans 
** jamais appeler setPoissons. En définissant by_reference à false, setPoissons sera appelé
*/
      ->add('sources', 'collection', array(
        'type' => new SourceType(),
        'allow_add'    => true,
        'allow_delete' => true,
        'by_reference' => false,
        ));
    }

  public function getName()
  {
    return 'poissonFamilleType';
  }
}