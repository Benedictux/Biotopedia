<?php
//src/Biotopedia/CoreBundle/Form/Type/SourceType.php
namespace Biotopedia\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SourceType extends AbstractType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Biotopedia\CoreBundle\Entity\Source'
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label' => 'Nom :'))
            ->add('href','url', array('label' => 'Adresse web de la source :', 'required' => false))
            ->add('title','text', array('label' => 'Informations sur la source :', 'required' => false))
            ->add('hreflang', 'choice', array(
                'label' => 'Langue de la source :',
                'required' => false,
                'multiple'  => false,
                'expanded' => true,
                'choices'   => array(
                  'fr' => 'Français',
                  'en' => 'Anglais',
                  'de' => 'Allemand',
                  'it' => 'Italien',
                  'ne' => 'Néerlandais',
                  'es' => 'Espagnol')))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sourceType';
    }
}
