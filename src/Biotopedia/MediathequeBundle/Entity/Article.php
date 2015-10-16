<?php
//src/Biotopedia/MediathequeBundle/Entity/Article.php
namespace Biotopedia\MediathequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Pour la notation
use Symfony\Component\Validator\Constraints as Assert; //Pour formulaires composés de plusieus Type
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Biotopedia\MediathequeBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
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
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var datetime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publie", type="boolean")
     */
    private $publie;

    /**
     * @ORM\ManyToOne(targetEntity="Categorie",inversedBy="articles")
     */    
    private $categorie;

    //Création de la propriété $auteur. L'objet Article est liè à 1'objet User,
    //cela stocke l'ID de l'User. * @ORM\JoinColumn(nullable=false), force le fait 
    //est qu'un article doit forcement apartenir à un User
    //La relation devient bidirectionnelle par rajout de paramètre 'inversedBy' 
    //dans l'annotation Many-To-One correspondant au symétrique de 'mappedBy' de 
    //User: private $articles
    /**
     * @ORM\ManyToOne(targetEntity="Biotopedia\UsersBundle\Entity\User", inversedBy="articles", cascade="merge")
     */    
    private $auteur;

///////////////////////////////////////////////////////////////////////////////////////////////////////

    public function __construct()
    {
        $this->created  = new\Datetime();
        $this->publie   = false;
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////
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
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publie
     *
     * @param boolean $publie
     * @return Article
     */
    public function setPublie($publie)
    {
        $this->publie = $publie;
    
        return $this;
    }

    /**
     * Get publie
     *
     * @return boolean 
     */
    public function getPublie()
    {
        return $this->publie;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Article
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Article
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set categorie
     *
     * @param \Biotopedia\MediathequeBundle\Entity\Categorie $categorie
     * @return Article
     */
    public function setCategorie(\Biotopedia\MediathequeBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Biotopedia\MediathequeBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set auteur
     *
     * @param \Biotopedia\UsersBundle\Entity\User $user
     * @return Article
     */
    public function setAuteur(\Biotopedia\UsersBundle\Entity\User $user)
    {
        $this->auteur = $user;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Biotopedia\UsersBundle\Entity\User 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getCategorie()->increaseArticle();
        $this->getAuteur()->increaseArticle();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getCategorie()->decreaseArticle();
        $this->getAuteur()->decreaseArticle();
    }
//////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->setUpdated(new \Datetime());
    }
}