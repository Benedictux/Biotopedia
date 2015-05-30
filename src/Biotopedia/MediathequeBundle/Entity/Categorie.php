<?php
//src/Biotopedia/MediathequeBundle/Entity/Categorie.php
namespace Biotopedia\MediathequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Pour la notation
use Symfony\Component\Validator\Constraints as Assert; //Pour formulaires composés de plusieus Type
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Biotopedia\MediathequeBundle\Entity\CategorieRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Categorie
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image_directory", type="string")
     */
    private $image_directory;

    //Chaque Categorie néccesite une Image (JoinColumn(nullable=false)), donc cascade sur persite car 
    //on doit persiter une Image quand je persite une Categorie.
    /**
    * @ORM\OneToOne(targetEntity="Biotopedia\CoreBundle\Entity\Image", cascade={"persist", "remove", "merge"})
    */
    private $image;

    /**
     * @ORM\Column(name="nb_articles", type="integer")
     */
    private $nb_articles = 0;

    //'s' car une categorie est liée à plusieurs articles.
    //L'entité inverse Category doit être au courant des caractéristiques
    //de la relation, définies dans l'annotation de l'entité propriétaire Article.
    //Le mappedBy correspond donc à l'attribut de l'entité propriétaire (Article)
    //pointant vers l'entité inverse (Categorie) : private $categorie
    /**
    * @ORM\OneToMany(targetEntity="Article", mappedBy="categorie")
    */
    private $articles;

    ////////////////////////////////////////////////////////////////////////////////
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    ////////////////////////////////////////////////////////////////////////////////

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
     * Set name
     *
     * @param string $name
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Categorie
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image_directory
     *
     * @param string $imageDirectory
     * @return Categorie
     */
    public function setImageDirectory($imageDirectory)
    {
        $this->image_directory = $imageDirectory;
    
        return $this;
    }

    /**
     * Get image_directory
     *
     * @return string 
     */
    public function getImageDirectory()
    {
        return $this->image_directory;
    }

    /**
     * Set image
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Image $image
     * @return Categorie
     */
    public function setImage(\Biotopedia\CoreBundle\Entity\Image $image = null)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return \Biotopedia\PisciothequeBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set nb_articles
     *
     * @param integer $nbArticles
     * @return Categorie
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
    
    //////////////////////////////////////////////////////////////////////////////    
    /**
     * Add articles
     *
     * @param \Biotopedia\MediathequeBundle\Entity\Article $articles
     * @return Categorie
     */
    public function addArticle(\Biotopedia\MediathequeBundle\Entity\Article $article)
    {
        $this->articles[] = $article;
    
        // On lie l'article à la categorie
        $article->setCategorie($this);

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

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preCreation()
    {
        // Si jamais il n'y a pas de fichier (file = champ facultatif)
        if (null === $this->name) {
            return;
        }

        // Ecriture du chemin relatif vers le dossier cible (src/Biotopedia/PisciothequeBundle/Resources/public/images/Familles/ScientificName/)
        $this->image_directory = __DIR__.'/../../../../web/uploads/img/Categories/'.$this->name;

        //Transmission à l'entité Image du upload_dir et du path
        $this->getImage()->setUploadDir($this->image_directory);

        $webPath = "uploads/img/Categories/".$this->name;
        $this->getImage()->setPath($webPath);
    }

    //Création du répertoire du name de la Categorie pour stocker les photos.
    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function createImageDir()
    {
        if(!is_dir($this->getImageDirectory())){
            mkdir($this->getImageDirectory(), 0777, true);
        }
    }

    //Supression récursive du dossier $this->getImageDirectory().
    /**
    * @ORM\PostRemove()
    */
    public function rmDirRecursive() {
       if (is_dir($this->getImageDirectory())) {
         $objects = scandir($this->getImageDirectory());
         foreach ($objects as $object) {
           if ($object != "." && $object != "..") {
             if (filetype($this->getImageDirectory()."/".$object) == "dir") rrmdir($this->getImageDirectory()."/".$object); else unlink($this->getImageDirectory()."/".$object);
           }
         }
         reset($objects);
         rmdir($this->getImageDirectory());
       }
    } 

}