<?php
//src/Biotopedia/PisciothequeBundle/Form/Type/PoissonType.php
namespace Biotopedia\PisciothequeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Biotopedia\CoreBundle\Form\Type\ImageType;

class PoissonType extends AbstractType
{
  //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		//'cascade_validation' => true, active la validation sur FamilleType
    	$resolver->setDefaults(array(
        	'data_class' => 'Biotopedia\PisciothequeBundle\Entity\Poisson',
        	'cascade_validation' => true
    	));
	}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('famille', 'entity', array(
            'class'    => 'BiotopediaPisciothequeBundle:Famille',
            'property' => 'scientific_name',//appelle getscientificName de la class Famille
            'multiple' => false,
            'expanded' => true,
            'mapped' => false
            ))
        ->add('common_name', 'text', array('label' => 'Nom commun', 'required' => false))
        ->add('scientific_name', 'text', array('label' => 'Nom scientifique', 'required' => false))
        ->add('description', 'textarea', array('label' => 'Déscription', 'required' => false))
        ->add('image', new ImageType(), array('label' => 'Ajouter une photo'));
    }

    public function getName()
    {
        return 'poissonType';
    }
}