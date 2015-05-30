<?php
// src/Biotopedia/CoreBundle/Bigbrother/BigbrotherEvents.php
namespace Biotopedia\CoreBundle\Bigbrother;

//Cette classe ne fait rien, elle ne sert qu'à faire la correspondance entre BigbrotherEvents::onMessagePost
//utiliser pour déclencher l'évènement et le nom de l'évènement en lui même biotopedia_core.bigbrother.post_message.
//Elle contient juste des constantes contenants le nom de nos évènements. C'est facultatif,
// mais c'est une bonne pratique qui évitera d'écrire directement le nom de l'évènement.
// On utilisera ainsi le nom de la constante, défini dans cette classe.
final class BigbrotherEvents
{
  const onMessagePost = 'biotopedia_core.bigbrother.post_message';
  // Vos autres évènements…
}