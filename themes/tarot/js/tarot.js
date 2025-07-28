jQuery(function ($) {
  // Fonction pour vérifier périodiquement si l'URL du tirage est disponible
  function verifierTirageURL(postID, attempts = 0) {
    const maxAttempts = 30; // 30 tentatives = 30 secondes max

    if (attempts >= maxAttempts) {
      console.error("Timeout : impossible de récupérer l'URL du tirage");
      return;
    }

    $.post(
      ajaxurl,
      {
        action: "get_tirage_url",
        post_id: postID,
      },
      function (response) {
        if (response.success && response.data.tirage_url) {
          console.log("URL du tirage trouvée :", response.data.tirage_url);
          window.location.href = response.data.tirage_url;
        } else {
          // Réessayer dans 1 seconde
          setTimeout(() => verifierTirageURL(postID, attempts + 1), 1000);
        }
      }
    ).fail(function () {
      // En cas d'erreur, réessayer
      setTimeout(() => verifierTirageURL(postID, attempts + 1), 1000);
    });
  }

  // Écouter l'événement d'envoi du formulaire CF7
  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      console.log("Formulaire envoyé, recherche de l'URL du tirage...");

      // Récupérer l'ID du post créé depuis la réponse du formulaire
      const response = event.detail.apiResponse;

      // Si l'ID du post est dans la réponse CF7
      if (response && response.post_id) {
        verifierTirageURL(response.post_id);
      } else {
        // Sinon, utiliser une approche alternative
        // Attendre un peu puis chercher le dernier post créé
        setTimeout(() => {
          $.post(
            ajaxurl,
            {
              action: "get_latest_tirage_post",
            },
            function (response) {
              if (response.success && response.data.post_id) {
                verifierTirageURL(response.data.post_id);
              }
            }
          );
        }, 1000);
      }
    },
    false
  );
});
