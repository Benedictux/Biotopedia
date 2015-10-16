<?php
// src/Biotopedia/CoreBundle/Entity/Source.php
namespace Biotopedia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Pour la notation
use Symfony\Component\Validator\Constraints as Assert; //Pour formulaires composés de plusieus Type

/**
 * Source
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Biotopedia\CoreBundle\Entity\SourceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Source
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var date
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="href", type="string", length=255, nullable=true)
     */
    private $href;

    /**
     * @var string
     *
     * @ORM\Column(name="hreflang", type="string", length=2, nullable=true)
     */
    private $hreflang;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
    * @ORM\ManyToOne(targetEntity="Biotopedia\PisciothequeBundle\Entity\Poisson", inversedBy="sources")
    */
    private $poisson;

///////////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct()
    {
        $this->created = new \Datetime();
    }
////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Source
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Source
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set href
     *
     * @param string $href
     * @return Source
     */
    public function setHref($href)
    {
        $this->href = $href;
    
        return $this;
    }

    /**
     * Get href
     *
     * @return string 
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set hreflang
     *
     * @param string $hreflang
     * @return Source
     */
    public function setHreflang($hreflang)
    {
        $this->hreflang = $hreflang;
    
        return $this;
    }

    /**
     * Get hreflang
     *
     * @return string 
     */
    public function getHreflang()
    {
        return $this->hreflang;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Source
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set poisson
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Poisson $poisson
     * @return Source
     */
    public function setPoisson(\Biotopedia\PisciothequeBundle\Entity\Poisson $poisson = null)
    {
        $this->poisson = $poisson;
    
        return $this;
    }

    /**
     * Get poisson
     *
     * @return \Biotopedia\PisciothequeBundle\Entity\Poisson 
     */
    public function getPoisson()
    {
        return $this->poisson;
    }
}