<?php
// src/Biotopedia/UsersBundle/Entity/User.php
namespace Biotopedia\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Biotopedia\UsersBundle\Entity\User
 *
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="Biotopedia\UsersBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
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
     * @var datetime
     *
     * @ORM\Column(name="lastlogin", type="datetime", nullable=true)
     */
    private $lastlogin;

    /**
     * @var datetime
     *
     * @ORM\Column(name="lastactivity", type="datetime", nullable=true)
     */
    private $lastactivity;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

//MES ATTRIBUTS PERSO/////////////////////////////////////////////////////////////////////////////////
    //Création de la propriété $familles. L'objet User est liè à 1'objet Famille,
    //cela stocke l'ID de la Famille.
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
    
    /**
    * @ORM\Column(name="nb_familles", type="integer")
    */
    private $nb_familles = 0;
    
    /**
    * @ORM\Column(name="nb_poissons", type="integer")
    */
    private $nb_poissons = 0;

    public function __construct()
    {
        $this->created  = new\DateTime();
        $this->roles = array("ROLE_USER");
        $this->enabled = false;
        $this->salt = md5(uniqid(null, true));
        $this->familles = new ArrayCollection();
        $this->poissons = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }
    
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
     * @return User
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
     * @return User
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
     * Set lastlogin
     *
     * @param \DateTime $lastlogin
     * @return User
     */
    public function setLastlogin($lastlogin = null)
    {
        $this->lastlogin = $lastlogin;
    
        return $this;
    }

    /**
     * Get lastlogin
     *
     * @return \DateTime 
     */
    public function getLastlogin()
    {
        return $this->lastlogin;
    }

    /**
     * Set lastactivity
     *
     * @param \DateTime $lastactivity
     * @return User
     */
    public function setLastactivity($lastactivity = null)
    {
        $this->lastactivity = $lastactivity;
    
        return $this;
    }

    /**
     * Get lastactivity
     *
     * @return \DateTime 
     */
    public function getLastactivity()
    {
        return $this->lastactivity;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */

    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
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
    
    /**
     * Set nb_poissons
     *
     * @param integer $nbPoissons
     * @return User
     */
    public function setNbPoissons($nbPoissons)
    {
        $this->nb_poissons = $nbPoissons;
    
        return $this;
    }

    /**
     * Get nb_poissons
     *
     * @return integer 
     */
    public function getNbPoissons()
    {
        return $this->nb_poissons;
    }

    /**
     * Set nb_familles
     *
     * @param integer $nbFamilles
     * @return User
     */
    public function setNbFamilles($nbFamilles)
    {
        $this->nb_familles = $nbFamilles;
    
        return $this;
    }

    /**
     * Get nb_familles
     *
     * @return integer 
     */
    public function getNbFamilles()
    {
        return $this->nb_familles;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // Ici nous n'avons rien à effacer. 
        // Cela aurait été le cas si nous avions un mot de passe en clair.
    }

   /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Methode à implementer obligatoirement pour le use Symfony\Component\Security\Core\User\AdvancedUserInterface;//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function isAccountNonExpired()
    {
        return true;
    }
    public function isAccountNonLocked()
    {
        return true;
    }
    public function isCredentialsNonExpired()
    {
        return true;
    }
    public function isEnabled()
    {
        return true;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Mes Methodes//////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function setRoles(array $roles)
    {
        $this->roles = array();
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
        return $this;
    }
    /**
     * Add familles
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Famille $familles
     * @return User
     */
    public function addFamille(\Biotopedia\PisciothequeBundle\Entity\Famille $familles)
    {
        $this->familles[] = $familles;
    
        return $this;
    }

    /**
     * Remove familles
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Famille $familles
     */
    public function removeFamille(\Biotopedia\PisciothequeBundle\Entity\Famille $familles)
    {
        $this->familles->removeElement($familles);
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

    /**
     * Add poissons
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Poisson $poissons
     * @return User
     */
    public function addPoisson(\Biotopedia\PisciothequeBundle\Entity\Poisson $poissons)
    {
        $this->poissons[] = $poissons;
    
        return $this;
    }

    /**
     * Remove poissons
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Poisson $poissons
     */
    public function removePoisson(\Biotopedia\PisciothequeBundle\Entity\Poisson $poissons)
    {
        $this->poissons->removeElement($poissons);
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

    /**
     * Add articles
     *
     * @param \Biotopedia\MediathequeBundle\Entity\Article $articles
     * @return User
     */
    public function addArticle(\Biotopedia\MediathequeBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        // Je lie l'article à l'auteur
        $article->setAuteur($this);
    
        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Biotopedia\MediathequeBundle\Entity\Article $articles
     */
    public function removeArticle(\Biotopedia\MediathequeBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
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

    public function increaseFamille()
    {
        $this->nb_familles++;
    }

    public function decreaseFamille()
    {
        $this->nb_familles--;
    }

        public function increasePoisson()
    {
        $this->nb_poissons++;
    }

    public function decreasePoisson()
    {
        $this->nb_poissons--;
    }

    public function inActivity()
    {
        $this->setLastactivity(new \DateTime());
    }
    //////////////////////////////////////////////////////////////////////////////////
    /**
    * @ORM\PreUpdate()
    */
    public function preUpdate()
    {
        $this->setUpdated(new \Datetime());
    }
    //////////////////////////////////////////////////////////////////////////////////

}