// src/Biotopedia/PisciothequeBundle/Resources/public/Poisson/Poisson.js

// Voici le script en question, utilisat la bibliotheque jQuery préablement chargé :
    $(document).ready(function() {
      // On récupère la balise <div> contenant l'attribut « data-prototype » d'id="poissonEditType_sources".
      var $container = $('div#poissonEditType_sources');
  
      // On ajoute un lien pour ajouter un nouveau poisson
      var $addLink = $('<a href="#" id="add_poisson" class="bouton-form-add">Ajouter une source</a>');
      $container.append($addLink);
  
      // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
      $addLink.click(function(e) {
        addSource($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
      });
  
      // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
      var index = $container.find(':input').length;

      // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle famille par exemple).
      if (index == 0) {
        addSource($container);
      } else {
         // Pour chaque Source déjà existante, on ajoute un lien de suppression
        $container.children('div').each(function() {
          addDeleteLink($(this));
        });
       }
  
      // La fonction qui ajoute un formulaire Poisson
      function addSource($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($container.attr('data-prototype')
            .replace(/__name__label__/g, 'Source n°' + (index +1))
            .replace(/__name__/g, index));
  
        // On ajoute au prototype un lien pour pouvoir supprimer la source
        addDeleteLink($prototype);
  
        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
  
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
      }
  
       // La fonction qui ajoute un lien de suppression d'une Source
       function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="bouton-form-delete">Supprimer</a>');
  
        // Ajout du lien
        $prototype.append($deleteLink);
  
        // Ajout du listener sur le clic du lien
         $deleteLink.click(function(e) {
          $prototype.remove();
          e.preventDefault(); // évite qu'un # apparaisse dans l'URL
          return false;
        });
      }
    });