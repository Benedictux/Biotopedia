<?php
// src/Biotopedia/CoreBundle/Bigbrother/MessagePostEvent.php
namespace Biotopedia\CoreBundle\Bigbrother;

use Symfony\Component\EventDispatcher\Event;
use Biotopedia\UsersBundle\Entity\User;

//La classe de l'évènement, c'est, la classe de l'objet que le gestionnaire d'évènements va transmettre 
//aux listeners. C'est lui qui déclenche l'évènement et qui transmet une instance de cette classe.
//Voici le squelette commun à tous les évènements. La classe étend la classe Event du composant EventDispatcher.
class MessagePostEvent extends Event
{
  protected $message;
  protected $user;

  public function __construct($message, User $user)
  {
    $this->message = $message;
    $this->user    = $user;
  }

  // Le listener doit avoir accès au message
  public function getMessage()
  {
    return $this->message;
  }

  // Le listener doit pouvoir modifier le message
  public function setMessage($message)
  {
    return $this->message = $message;
  }

  // Le listener doit avoir accès à l'utilisateur
  public function getUser()
  {
    return $this->user;
  }
  // Pas de setUser, le listener ne peut pas modifier l'auteur du message !
}