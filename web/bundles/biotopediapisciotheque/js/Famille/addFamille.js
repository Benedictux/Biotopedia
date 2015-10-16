// src/Biotopedia/PisciothequeBundle/Resources/public/Famille/Famille.js

// Voici le script en question, utilisat la bibliotheque jQuery préablement chargé :
    $(document).ready(function() {
      // On récupère la balise <div> contenant l'attribut « data-prototype » d'id="familleType_poissons".
      var $container = $('div#familleType_poissons');
  
      // On ajoute un lien pour ajouter un nouveau poisson
      var $addLink = $('<a href="#" id="add_poisson" class="bouton-form-add">Ajouter un poisson</a>');
      $container.append($addLink);
  
      // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
      $addLink.click(function(e) {
        addPoisson($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
      });
  
      // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
      var index = $container.find(':input').length;

      // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle famille par exemple).
      if (index == 0) {
        addPoisson($container);
      } else {
         // Pour chaque poisson déjà existante, on ajoute un lien de suppression
        $container.children('div').each(function() {
          addDeleteLink($(this));
        });
       }
  
      // La fonction qui ajoute un formulaire Poisson
      function addPoisson($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Poisson n°' + (index +1))
            .replace(/__name__/g, index));
  
        // On ajoute au prototype un lien pour pouvoir supprimer le poisson
        addDeleteLink($prototype);
  
        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
  
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
      }
  
       // La fonction qui ajoute un lien de suppression d'un poisson
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