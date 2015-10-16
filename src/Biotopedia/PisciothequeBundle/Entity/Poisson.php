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
     * @ORM\Column(name="type", type="text", length=50)
     * @Assert\NotBlank()
     */
    private $type;

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
     * @ORM\Column(name="common_name", unique=true, type="string", length=100, nullable=true)
     */
    private $common_name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="origine", type="text", nullable=true)
     */
    private $origine;

    /**
     * @var string
     *
     * @ORM\Column(name="difficulte", type="text", nullable=true)
     */
    private $difficulte;

    /**
     * @var string
     *
     * @ORM\Column(name="dimorphisme_sexuel", type="text", nullable=true)
     */
    private $dimorphisme_sexuel;

    /**
     * @var string
     *
     * @ORM\Column(name="zone", type="text", nullable=true)
     */
    private $zone;


    /**
     * @var string
     *
     * @ORM\Column(name="comportement_social", type="text", nullable=true)
     */
    private $comportement_social;

    /**
     * @var string
     *
     * @ORM\Column(name="reproduction", type="text", nullable=true)
     */
    private $reproduction;

    /**
     * @var integer
     *
     * @ORM\Column(name="taille", type="integer", nullable=true)
     */
    private $taille;

    /**
     * @var float
     *
     * @ORM\Column(name="temperature", type="float", nullable=true)
     */
    private $temperature;

    /**
     * @var float
     *
     * @ORM\Column(name="ph", type="float", nullable=true)
     */
    private $ph;

    /**
     * @var integer
     *
     * @ORM\Column(name="durete", type="integer", nullable=true)
     */
    private $durete;

    // Attribut pour stocker le nom du dossier temporairement lors de la suppression
    /**
     * @var string
     *
     * @ORM\Column(name="temp_directoryname", type="string", length=255, nullable=true)
     */
    private $temp_directoryname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image_directory", type="string")
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
     * @ORM\ManyToOne(targetEntity="Famille", inversedBy="poissons")
     */    
    private $famille;

    /**
    * @ORM\ManyToMany(targetEntity="Biotopedia\UsersBundle\Entity\User", inversedBy="poissons")
    */
    private $auteurs;

    /**
    * @ORM\OneToMany(targetEntity="Biotopedia\CoreBundle\Entity\Source", mappedBy="poisson", cascade={"persist", "remove"})
    */
    private $sources;

///////////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct()
    {
        $this->created = new \Datetime();
        $this->auteurs = new ArrayCollection();
        $this->sources = new ArrayCollection();
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
    public function setCreated(\Datetime $created)
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
     * Set type
     *
     * @param string $type
     * @return Poisson
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
     * Set origine
     *
     * @param string $origine
     * @return Poisson
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;
    
        return $this;
    }

    /**
     * Get origine
     *
     * @return string 
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * Set difficulte
     *
     * @param string $difficulte
     * @return Poisson
     */
    public function setDifficulte($difficulte)
    {
        $this->difficulte = $difficulte;
    
        return $this;
    }

    /**
     * Get difficulte
     *
     * @return string 
     */
    public function getDifficulte()
    {
        return $this->difficulte;
    }

    /**
     * Set dimorphisme_sexuel
     *
     * @param string $dimorphismeSexuel
     * @return Poisson
     */
    public function setDimorphismeSexuel($dimorphismeSexuel)
    {
        $this->dimorphisme_sexuel = $dimorphismeSexuel;
    
        return $this;
    }

    /**
     * Get dimorphisme_sexuel
     *
     * @return string 
     */
    public function getDimorphismeSexuel()
    {
        return $this->dimorphisme_sexuel;
    }

    /**
     * Set zone
     *
     * @param string $zone
     * @return Poisson
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    
        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set comportement_social
     *
     * @param string $comportementSocial
     * @return Poisson
     */
    public function setComportementSocial($comportementSocial)
    {
        $this->comportement_social = $comportementSocial;
    
        return $this;
    }

    /**
     * Get comportement_social
     *
     * @return string 
     */
    public function getComportementSocial()
    {
        return $this->comportement_social;
    }

    /**
     * Set reproduction
     *
     * @param string $reproduction
     * @return Poisson
     */
    public function setReproduction($reproduction)
    {
        $this->reproduction = $reproduction;
    
        return $this;
    }

    /**
     * Get reproduction
     *
     * @return string 
     */
    public function getReproduction()
    {
        return $this->reproduction;
    }

    /**
     * Set taille
     *
     * @param integer $taille
     * @return Poisson
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;
    
        return $this;
    }

    /**
     * Get taille
     *
     * @return integer 
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set temperature
     *
     * @param float $temperature
     * @return Poisson
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    
        return $this;
    }

    /**
     * Get temperature
     *
     * @return float 
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set ph
     *
     * @param float $ph
     * @return Poisson
     */
    public function setPh($ph)
    {
        $this->ph = $ph;
    
        return $this;
    }

    /**
     * Get ph
     *
     * @return float 
     */
    public function getPh()
    {
        return $this->ph;
    }

    /**
     * Set durete
     *
     * @param integer $durete
     * @return Poisson
     */
    public function setDurete($durete)
    {
        $this->durete = $durete;
    
        return $this;
    }

    /**
     * Get durete
     *
     * @return integer 
     */
    public function getDurete()
    {
        return $this->durete;
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
     * Set temp_directoryname
     *
     * @param string $tempDirectoryname
     * @return Poisson
     */
    public function setTempDirectoryname($tempDirectoryname)
    {
        $this->temp_directoryname = $tempDirectoryname;
    
        return $this;
    }

    /**
     * Get temp_directoryname
     *
     * @return string 
     */
    public function getTempDirectoryname()
    {
        return $this->temp_directoryname;
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
    public function addAuteur(\Biotopedia\UsersBundle\Entity\User $user)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->auteurs[] = $user;

        return $this;
    }

    /**
    * Remove users
    *
    * @param \Biotopedia\UsersBundle\Entity\User $user
    */
    public function removeAuteur(\Biotopedia\UsersBundle\Entity\User $user)
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
     * Add sources
     *
     * @param \Biotopedia\CoreBundle\Entity\Source $sources
     * @return Poisson
     */
    public function addSource(\Biotopedia\CoreBundle\Entity\Source $source)
    {
        $this->sources[] = $source;
        
        // On lie la source au poisson
        $source->setPoisson($this);
    
        return $this;
    }

    /**
     * Remove sources
     *
     * @param \Biotopedia\CoreBundle\Entity\Source $sources
     */
    public function removeSource(\Biotopedia\CoreBundle\Entity\Source $source)
    {
        $this->sources->removeElement($source);

        // Si la relation est facultative (nullable=true) :
        $source->setPoisson(null);
    }

    /**
     * Get sources
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSources()
    {
        return $this->sources;
    }
    //////////////////////////////////////////////////////////////////////////////////
    /**
    * @ORM\PrePersist
    */
    public function increase()
    {
        $this->getFamille()->increasePoisson();
        $this->getAuteur()->increasePoisson();
    }

    /**
    * @ORM\PreRemove
    */
    public function decrease()
    {
        $this->getFamille()->decreasePoisson();
        $this->getAuteur()->decreasePoisson();
    }
    //////////////////////////////////////////////////////////////////////////////////
    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preCreation()
    {
        // Si image_directory existe c'est un update 
        if(null !== $this->image_directory){
            $this->temp_directoryname = $this->image_directory;
            $this->setUpdated(new \Datetime());
        }

        // Ecriture du chemin relatif vers le dossier cible (src/Biotopedia/PisciothequeBundle/Resources/public/images/Poissons/ScientificName/)
        $this->image_directory = __DIR__.'/../../../../web/uploads/img/Poissons/'.$this->scientific_name;

        //Transmission à l'entité Image du upload_dir et du path
        $this->getImage()->setUploadDir($this->image_directory);
        $webPath = "uploads/img/Poissons/".$this->scientific_name;
        $this->getImage()->setPath($webPath);
    }

    //Création du répertoire du scientific_name du Poisson pour stocker les photos.
    /**
    * @ORM\PostPersist()
    */
    public function createImageDir()
    {
        // Si on as pas de dossier on le créer
        if (!is_dir($this->getImageDirectory()))
        {
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
    //////////////////////////////////////////////////////////////////////////////////
}