<?php
//src/Biotopedia/CoreBundle/Form/Type/ImageType.php
namespace Biotopedia\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    //Facultatif mais c'est mieux de déclaré la class ratachée à ce formulaire
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Biotopedia\CoreBundle\Entity\Image'
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'imageType';
    }
}
