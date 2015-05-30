<?php
//src/Biotopedia/PisciothequeBundle/Entity/Poisson.php
namespace Biotopedia\PisciothequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Pour la notation
use Symfony\Component\Validator\Constraints as Assert; //Pour formulaires composés de plusieus Type
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Poisson
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Biotopedia\PisciothequeBundle\Entity\PoissonRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Poisson
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
     * @ORM\Column(name="created", type="date")
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
     * @ORM\Column(name="scientific_name", unique=true, type="string", length=100)
     * @Assert\NotBlank()
     */
    private $scientific_name;

    /**
     * @var string
     *
     * @ORM\Column(name="common_name", unique=true, type="string", length=100)
     */
    private $common_name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image_directory", type="text")
     */
    private $image_directory;

    //Chaque Poisson néccesite une Image (JoinColumn(nullable=false)), donc cascade sur persite car 
    //on doit persiter une Image quand je persite une Famille.
    //'s' car une famille peut être liée à plusieurs images.
    //L'entité inverse Famille doit être au courant des caractéristiques
    //de la relation, définies dans l'annotation de l'entité propriétaire Image.
    //Le mappedBy correspond donc à l'attribut de l'entité propriétaire (Image)
    //pointant vers l'entité inverse (Famille) : private $famille
    /**
    * @ORM\OneToOne(targetEntity="Biotopedia\CoreBundle\Entity\Image", cascade={"persist", "remove", "merge"})
    */
    private $image;

    //Création de la propriété $famille. L'objet Poisson est liè à 1'objet Famille,
    //cela stocke l'ID de la Famille. * @ORM\JoinColumn(nullable=false), force le fait 
    //est qu'un poisson doit forcement apartenir à une Famille
    // * @ORM\JoinColumn(name="famille_id", referencedColumnName="id")
    //La relation devient bidirectionnelle par rajout de paramètre 'inversedBy' 
    //dans l'annotation Many-To-One correspondant au symétrique de 'mappedBy' de 
    //Famille: private $poissons
    /**
     * @ORM\ManyToOne(targetEntity="Famille", inversedBy="poissons", cascade="merge")
     */    
    private $famille;

    /**
    * @ORM\ManyToMany(targetEntity="Biotopedia\UserBundle\Entity\User", inversedBy="poissons")
    */
    private $auteurs;

///////////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct()
    {
        $this->created = new \Datetime();
        $this->auteurs = new ArrayCollection();
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
     * Set created
     *
     * @param \DateTime $created
     * @return Poisson
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
     * @return Poisson
     */
    public function setUpdated(\Datetime $updated)
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
     * Set scientific_name
     *
     * @param string $scientific_name
     * @return Poisson
     */
    public function setScientificName($scientific_name)
    {
        $this->scientific_name = $scientific_name;
    
        return $this;
    }

    /**
     * Get scientific_name
     *
     * @return string 
     */
    public function getScientificName()
    {
        return $this->scientific_name;
    }

    /**
     * Set common_name
     *
     * @param string $common_name
     * @return Poisson
     */
    public function setCommonName($common_name)
    {
        $this->common_name = $common_name;
    
        return $this;
    }

    /**
     * Get common_name
     *
     * @return string 
     */
    public function getCommonName()
    {
        return $this->common_name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Poisson
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
     * @return Poisson
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
     * @param \Biotopedia\CoreBundle\Entity\Image $image
     * @return Poisson
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
     * Set famille
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Famille $famille
     * @return Poisson
     */
    public function setFamille(\Biotopedia\PisciothequeBundle\Entity\Famille $famille = null)
    {
        $this->famille = $famille;
    
        return $this;
    }

    /**
     * Get famille
     *
     * @return \Biotopedia\PisciothequeBundle\Entity\Famille 
     */
    public function getFamille()
    {
        return $this->famille;
    }
    //////////////////////////////////////////////////////////////////////////////////
    /**
    * Add users
    *
    * @param \Biotopedia\UserBundle\Entity\User $user
    * @return Poisson
    */
    public function addAuteur(\Biotopedia\UserBundle\Entity\User $user)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->auteurs[] = $user;

        return $this;
    }

    /**
    * Remove users
    *
    * @param \Biotopedia\UserBundle\Entity\User $user
    */
    public function removeAuteur(\Biotopedia\UserBundle\Entity\User $user)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->auteurs->removeElement($user);
    }

    /**
    * Get auteurs
    *
    * @return \Doctrine\Common\Collections\Collection 
    */
    // Notez le pluriel, on récupère une liste de catégories ici !
    public function getAuteurs()
    {
        return $this->auteurs;
    }

    //////////////////////////////////////////////////////////////////////////////////
    /**
    * @ORM\PrePersist
    */
    public function increase()
    {
        $this->getFamille()->increasePoisson();
    }

    /**
    * @ORM\PreRemove
    */
    public function decrease()
    {
        $this->getFamille()->decreasePoisson();
    }
    //////////////////////////////////////////////////////////////////////////////////
    /**
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->setUpdated(new \Datetime());
    }

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preCreation()
    {
        // Si jamais il n'y a pas de fichier (file = champ facultatif)
        if (null === $this->scientific_name) {
            return;
        }

        // Ecriture du chemin relatif vers le dossier cible (src/Biotopedia/PisciothequeBundle/Resources/public/images/Familles/ScientificName/)
        $this->image_directory = __DIR__.'/../../../../web/uploads/img/Poissons/'.$this->scientific_name;

        //Renome l'ancien dossier d'image si il existe par le nouveau
        if(is_dir($this->getImage()->getUploadDir())){
            rename ($this->getImage()->getUploadDir() , $this->image_directory);
        }

        //Transmission à l'entité Image du upload_dir et du path
        $this->getImage()->setUploadDir($this->image_directory);

        $webPath = "uploads/img/Poissons/".$this->scientific_name;
        $this->getImage()->setPath($webPath);
    }

    //Création du répertoire du scientific_name du Poisson pour stocker les photos.
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