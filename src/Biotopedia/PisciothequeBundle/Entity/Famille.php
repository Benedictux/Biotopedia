<?php
//src/Biotopedia/PisciothequeBundle/Entity/Famille.php
namespace Biotopedia\PisciothequeBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Pour la notation
use Symfony\Component\Validator\Constraints as Assert; //Pour formulaires composés de plusieus Type
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Famille
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Biotopedia\PisciothequeBundle\Entity\FamilleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Famille
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
     * @ORM\Column(name="scientific_name", unique=true, type="string", length=100)
     * @Assert\NotBlank()
     */
    private $scientific_name;

    /**
     * @var string
     *
     * @ORM\Column(name="common_name", unique=true, type="string", length=100)
     * @Assert\NotBlank()
     */
    private $common_name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @ORM\Column(name="image_directory", type="text")
     */
    private $image_directory;

    //Chaque Famille néccesite une Image (JoinColumn(nullable=false)), donc cascade sur persite car 
    //on doit persiter une Image quand je persite une Famille.
    /**
    * @ORM\OneToOne(targetEntity="Biotopedia\CoreBundle\Entity\Image", cascade={"persist", "remove", "merge"})
    */
    private $image;

    //'s' car une famille est liée à plusieurs poissons.
    //L'entité inverse Famille doit être au courant des caractéristiques
    //de la relation, définies dans l'annotation de l'entité propriétaire Poisson.
    //Le mappedBy correspond donc à l'attribut de l'entité propriétaire (Poisson)
    //pointant vers l'entité inverse (Famille) : private $famille
    /**
    * @ORM\OneToMany(targetEntity="Poisson", mappedBy="famille", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
    */
    private $poissons;

    /**
    * @ORM\Column(name="nb_poissons", type="integer")
    */
    private $nb_poissons = 0;

    /**
    * @ORM\ManyToMany(targetEntity="Biotopedia\UsersBundle\Entity\User", inversedBy="familles")
    */
    private $auteurs;

    ////////////////////////////////////////////////////////////////////////////////
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created  = new\DateTime();
        $this->poissons = new ArrayCollection();
        $this->auteurs = new ArrayCollection();
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
     * Set created
     *
     * @param \DateTime $created
     * @return Famille
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
     * @return Famille
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
     * @return Famille
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
     * @return Famille
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
     * @return Famille
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
     * Set temp_directoryname
     *
     * @param string $tempDirectoryname
     * @return Famille
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
     * Set image_directory
     *
     * @param string $imageDirectory
     * @return Famille
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
    
    // rajouter $image = null: public function setImage(\Biotopedia\PisciothequeBundle\Entity\Image $image = null)
    // en cas de relation facultative.
    /**
     * Set image
     *
     * @param \Biotopedia\CoreBundle\Entity\Image $image
     * @return Famille
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
     * Set nbPoissons
     *
     * @param integer $nbPoissons
     * @return Famille
     */
    public function setNbPoissons($nbPoissons)
    {
        $this->nb_poissons = $nbPoissons;
    
        return $this;
    }

    /**
     * Get nbPoissons
     *
     * @return integer 
     */
    public function getNbPoissons()
    {
        return $this->nb_poissons;
    }

    ////////////////////////////////////////////////////////////////////////////
    /**
     * Add poissons
     *
     * @param \Biotopedia\PisciothequeBundle\Entity\Poisson $poisson
     * @return Famille
     */
    public function addPoisson(\Biotopedia\PisciothequeBundle\Entity\Poisson $poisson)
    {
        $this->poissons[] = $poisson;

        // On lie le poisson à la famille
        $poisson->setFamille($this);
    
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
    //////////////////////////////////////////////////////////////////////////////////
    /**
    * Add users
    *
    * @param \Biotopedia\UsersBundle\Entity\User $user
    * @return Famille
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
    public function increasePoisson()
    {
        $this->nb_poissons++;
    }

    public function decreasePoisson()
    {
        $this->nb_poissons--;
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getAuteur()->increaseFamille();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getAuteur()->decreaseFamille();
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

        // Ecriture du chemin relatif vers le dossier cible (src/Biotopedia/PisciothequeBundle/Resources/public/images/Familles/ScientificName/)
        $this->image_directory = __DIR__.'/../../../../web/uploads/img/Familles/'.$this->scientific_name;

        //Transmission à l'entité Image du upload_dir et du path
        $this->getImage()->setUploadDir($this->image_directory);
        $webPath = "uploads/img/Familles/".$this->scientific_name;
        $this->getImage()->setPath($webPath);
    }

    //Création du répertoire du scientific_name de la Famille pour stocker les photos.
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