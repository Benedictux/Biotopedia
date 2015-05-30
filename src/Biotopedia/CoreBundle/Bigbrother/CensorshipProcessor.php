<?php
// src/Biotopedia/CoreBundle/Bigbrother/CensorshipProcessor.php
namespace Biotopedia\CoreBundle\Bigbrother;

use Biotopedia\UserBundle\Entity\User;

class CensorshipProcessor
{
	protected $mailer;

	public function __construct(\Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	// Méthode pour notifier par e-mail un administrateur
	public function notifyEmail($message, User $user)
	{
	    $message = \Swift_Message::newInstance()
	      ->setSubject("Nouveau message d'un utilisateur surveillé")
	      ->setFrom('admin@votresite.com')
	      ->setTo('admin@votresite.com')
	      ->setBody("L'utilisateur surveillé '".$user->getUsername()."' a posté le message suivant : '".$message."'");
	    $this->mailer->send($message);
	}

	// Méthode pour censurer un message (supprimer les mots interdits)
	public function censorMessage($message)
	{
		$message = str_replace(array(
			'fuck',
			'merde',
			'putain',
			'chier',
			'pute',
			'enculer',
			'connard',
			'nique',
			'cul'), 'MOTS CENSURÉ', $message);
	    return $message;
	}
}