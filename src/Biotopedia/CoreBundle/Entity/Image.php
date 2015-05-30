<?php
// src/Biotopedia/CoreBundle/Entity/Image.php
namespace Biotopedia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Pour la notation
use Symfony\Component\Validator\Constraints as Assert; //Pour formulaires composés de plusieus Type
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Biotopedia\CoreBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;


    // Attribut pour stocker le nom du fichier temporairement lors de la suppression
    /**
     * @var string
     *
     * @ORM\Column(name="temp_filename", type="string", length=255)
     */
    private $temp_filename;

    // Attribut pour stocker le chemin de l'image
    /**
     * @var string
     *
     * @ORM\Column(name="path", unique=true, nullable=true, type="string", length=255)
     */
    private $path;

    // Attribut pour stocker le chemin du dossier d'upload
    /**
     * @var string
     *
     * @ORM\Column(name="upload_dir", type="string", length=255)
     */
    private $upload_dir;

    //Pas d'annotation pour Doctrine car $file ne sera pas persister.
    //Par contre, c'est bien cet attribut qui servira pour le formulaire, et non les autres.
    private $file;

    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct()
    {
        $this->created     = new \Datetime;
        $this->temp_filename     = "1";

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
     * @return Image
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
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    
        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set temp_filename
     *
     * @param string $tempFilename
     * @return Image
     */
    public function setTempFilename($tempFilename)
    {
        $this->temp_filename = $tempFilename;
    
        return $this;
    }

    /**
     * Get temp_filename
     *
     * @return string 
     */
    public function getTempFilename()
    {
        return $this->temp_filename;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set upload_Dir
     *
     * @param string $uploadDir
     * @return Image
     */
    public function setUploadDir($uploadDir)
    {
        $this->upload_dir = $uploadDir;
    
        return $this;
    }

    /**
     * Get upload_Dir
     *
     * @return string 
     */
    public function getUploadDir()
    {
        return $this->upload_dir;
    }

    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->extension) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->temp_filename = $this->extension;

            // On réinitialise les valeurs des attributs extention et alt
            $this->extension = null;
            $this->alt = null;
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Facilite l'affichage en template de l'image.
    public function getWebPath()
    {
      return $this->getPath().'/'.$this->getId().'.'.$this->getExtension();
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload()
    {
        // Si jamais il n'y a pas de fichier (file = champ facultatif)
        if (null === $this->file) {
            return;
        }

        // Le nom du fichier est son id, on doit juste stocker également son extension
        $this->extension = $this->file->guessExtension();

        // Génération de l'attribut alt de la balise <img>, avec nom du fichier sur le PC de l'internaute
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
        return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->temp_filename)
        {
            $oldFile = $this->getUploadDir().'/'.$this->id.'.'.$this->temp_filename;

            if (file_exists($oldFile))
            {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix via getUploadRootDir ()
        //L'objet UploadedFile que le formulaire renvoie simplifie les choses avec sa méthode move().
        $this->file->move(
            $this->getUploadDir(), //getUploadDir(),  Le répertoire de destination
            $this->id.'.'.$this->extension   // Le nom du fichier à créer, ici « id.extension »
        );
    }

    /**
    * @ORM\PreRemove()
    */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id.
        // J'evite ainsi de supprimé l'image avant d'être sur que l'entité soit supprimé.
        $this->temp_filename = $this->getUploadDir().'/'.$this->getId().'.'.$this->getExtension();
    }

    /**
    * @ORM\PostRemove()
    */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->temp_filename)) {
            // On supprime le fichier
            unlink($this->temp_filename);
        }
    }
}