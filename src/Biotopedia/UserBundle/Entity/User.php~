<?php
// src/Biotopedia/UserBundle/Entity/User.php
namespace Biotopedia\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="BpUser")
 * @ORM\Entity(repositoryClass="Biotopedia\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    //Création de la propriété $familles. L'objet User est liè à 1'objet Famille,
    //cela stocke l'ID de la Famille. * @ORM\JoinColumn(nullable=false), force le fait 
    //est qu'un User doit forcement apartenir à une Famille
    // * @ORM\JoinColumn(name="famille_id", referencedColumnName="id")
    //La relation devient bidirectionnelle par rajout de paramètre 'inversedBy' 
    //dans l'annotation Many-To-One correspondant au symétrique de 'mappedBy' de 
    //Famille: private $poissons
    /**
     * @ORM\ManyToMany(targetEntity="Biotopedia\PisciothequeBundle\Entity\Famille", mappedBy="auteurs")
     */    
    private $familles;
    
    /**
     * @ORM\ManyToMany(targetEntity="Biotopedia\PisciothequeBundle\Entity\Poisson", mappedBy="auteurs")
     */    
    private $poissons;

    //'s' car un User est liée à plusieurs articles.
    //L'entité inverse Article doit être au courant des caractéristiques
    //de la relation, définies dans l'annotation de l'entité propriétaire User.
    //Le mappedBy correspond donc à l'attribut de l'entité propriétaire (User)
    //pointant vers l'entité inverse (Article) : private $auteur
    /**
    * @ORM\OneToMany(targetEntity="Biotopedia\MediathequeBundle\Entity\Article", mappedBy="auteur", cascade={"remove", "merge"}, orphanRemoval=true)
    */
    private $articles;

    /**
    * @ORM\Column(name="nb_articles", type="integer")
    */
    private $nb_articles = 0;

    /////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct()
    {
        parent::__construct();
        // Je détermine chaque membre au rôle minimum AUTEUR
        $this->roles = array('ROLE_AUTEUR');
        $this->familles = new ArrayCollection();
        $this->poissons = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /////////////////////////////////////////////////////////////////////////////////////
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
     * Set nb_articles
     *
     * @param integer $nbArticles
     * @return User
     */
    public function setNbArticles($nbArticles)
    {
        $this->nb_articles = $nbArticles;
    
        return $this;
    }

    /**
     * Get nb_articles
     *
     * @return integer 
     */
    public function getNbArticles()
    {
        return $this->nb_articles;
    }

   ////////////////////////////////////////////////////////////////////////////////////
    /**
     * Add familles
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Famille $familles
     * @return User
     */
    public function addFamille(\Biotopedia\PisciothequeBundle\Entity\Famille $famille)
    {
        $this->familles[] = $famille;
    
        return $this;
    }

    /**
     * Remove familles
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Famille $familles
     */
    public function removeFamille(\Biotopedia\PisciothequeBundle\Entity\Famille $famille)
    {
        $this->familles->removeElement($famille);
    }

    /**
     * Get familles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFamilles()
    {
        return $this->familles;
    }

    ////////////////////////////////////////////////////////////////////////////////////
    /**
     * Add poissons
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Poisson $poisson
     * @return User
     */
    public function addPoisson(\Biotopedia\PisciothequeBundle\Entity\Poisson $poisson)
    {
        $this->poissons[] = $poisson;
    
        return $this;
    }

    /**
     * Remove poissons
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Poisson $poissons
     */
    public function removePoisson(\Biotopedia\PisciothequeBundle\Entity\Poisson $poisson)
    {
        $this->poissons->removeElement($poisson);
    }

    /**
     * Get poissons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPoissons()
    {
        return $this->poissons;
    }
    ////////////////////////////////////////////////////////////////////////////
    /**
     * Add articles
     *
     * @param \Biotopedia\MediathequeBundle\Entity\Article $article
     * @return User
     */
    public function addArticle(\Biotopedia\MediathequeBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        // On lie l'article à l'auteur
        $article->setAuteur($this);
    
        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Biotopedia\MediathequeBundle\Entity\Article $articles
     */
    public function removeArticle(\Biotopedia\MediathequeBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
    //////////////////////////////////////////////////////////////////////////////////
    public function increaseArticle()
    {
        $this->nb_articles++;
    }

    public function decreaseArticle()
    {
        $this->nb_articles--;
    }
}