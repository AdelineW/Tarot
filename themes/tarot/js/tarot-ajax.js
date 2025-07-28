jQuery(function ($) {
  // Configuration
  const CONFIG = {
    maxAttempts: 30,
    checkInterval: 1000,
    messageSelector: "#message-attente",
    formSelector: "#wpcf7-f371-p372-o1", // Formulaire spécifique
  };

  // Vérifier si le formulaire cible existe sur la page
  if ($(CONFIG.formSelector).length === 0) {
    console.log("Formulaire cible non trouvé, script désactivé");
    return;
  }

  // Fonction pour afficher le message d'attente
  function afficherMessageAttente() {
    // Vérifier si le message n'est pas déjà affiché
    if ($(CONFIG.messageSelector).length > 0) {
      return;
    }

    const messageHtml = `
    <div id="message-attente" class="w-message color_red">
      <div class="w-message-body"><p>🔮 Votre tirage est en cours de préparation...<br>Merci de patienter quelques instants<div></div></p>
  </div>
    `;

    // Insérer le message après le formulaire spécifique
    $(CONFIG.formSelector).after(messageHtml);
    $(CONFIG.formSelector).hide();

    // Désactiver le bouton de soumission pour éviter les double-clics
    $(CONFIG.formSelector + " input[type='submit']").prop("disabled", true);
  }

  // Fonction pour masquer le message d'attente
  function masquerMessageAttente() {
    $(CONFIG.messageSelector).remove();
    $(CONFIG.formSelector).show();
    $(CONFIG.formSelector + " input[type='submit']").prop("disabled", false);
  }

  // Fonction pour mettre à jour le message d'attente
  function mettreAJourMessageAttente(texte, sousTexte = "") {
    if ($(CONFIG.messageSelector).length > 0) {
      $(CONFIG.messageSelector + " div:first").html(texte);
      if (sousTexte) {
        $(CONFIG.messageSelector + " div:nth-child(2)").html(sousTexte);
      }
    }
  }

  // Fonction pour afficher un message d'erreur
  function afficherErreur(message) {
    const erreurHtml = `
         <div  id="message-attente" class="message-erreur w-message color_red">
      <div class="w-message-body"><p><strong>Erreur :</strong> ${message}
        <br><br>
        <button onclick="location.reload()" style="w-btn us-btn-style_1">Réessayer</button></p>
  </div>
    `;

    if ($(CONFIG.messageSelector).length > 0) {
      $(CONFIG.messageSelector).replaceWith(erreurHtml);
    } else {
      $(CONFIG.formSelector).after(erreurHtml);
    }

    $(CONFIG.formSelector).show();
    $(CONFIG.formSelector + " input[type='submit']").prop("disabled", false);
  }

  // Fonction pour vérifier si l'URL du tirage est disponible
  function verifierTirageURL(postID, attempts = 0) {
    console.log(
      `Tentative ${attempts + 1}/${CONFIG.maxAttempts} pour le post ${postID}`
    );

    // Mettre à jour le message d'attente
    mettreAJourMessageAttente(
      "🔮 Finalisation de votre tirage...",
      `Vérification ${attempts + 1}/${CONFIG.maxAttempts}`
    );

    if (attempts >= CONFIG.maxAttempts) {
      console.error("Timeout : impossible de récupérer l'URL du tirage");
      afficherErreur(
        "Le tirage prend plus de temps que prévu. Veuillez réessayer."
      );
      return;
    }

    $.post(ajaxurl, {
      action: "get_tirage_url",
      post_id: postID,
    })
      .done(function (response) {
        if (response.success && response.data.tirage_url) {
          console.log("URL du tirage trouvée :", response.data.tirage_url);

          // Mettre à jour le message avant la redirection
          mettreAJourMessageAttente(
            "✨ Votre tirage est prêt !",
            "Redirection en cours..."
          );

          // Redirection après une petite pause
          setTimeout(() => {
            window.location.href = response.data.tirage_url;
          }, 1000);
        } else {
          setTimeout(
            () => verifierTirageURL(postID, attempts + 1),
            CONFIG.checkInterval
          );
        }
      })
      .fail(function (xhr, status, error) {
        console.error("Erreur AJAX:", error);
        setTimeout(
          () => verifierTirageURL(postID, attempts + 1),
          CONFIG.checkInterval
        );
      });
  }

  // Fonction pour récupérer le dernier post créé
  function recupererDernierPost() {
    return $.post(ajaxurl, {
      action: "get_latest_tirage_post",
    });
  }

  // Fonction pour vérifier si l'événement concerne le bon formulaire
  function estBonFormulaire(event) {
    return $(event.target).closest(CONFIG.formSelector).length > 0;
  }

  // Gestionnaire pour le début de soumission du formulaire
  document.addEventListener(
    "wpcf7submit",
    function (event) {
      // Vérifier si c'est le bon formulaire
      if (!estBonFormulaire(event)) {
        return;
      }

      console.log("Début de soumission du formulaire CF7 ciblé");

      // Afficher le message d'attente dès la soumission
      afficherMessageAttente();

      // Mettre à jour le message
      mettreAJourMessageAttente(
        "📤 Envoi de votre question...",
        "Connexion avec l'oracle en cours"
      );
    },
    false
  );

  // Gestionnaire pour l'envoi réussi du formulaire
  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      // Vérifier si c'est le bon formulaire
      if (!estBonFormulaire(event)) {
        return;
      }

      console.log("Formulaire CF7 ciblé envoyé avec succès");

      // S'assurer que le message d'attente est affiché
      if ($(CONFIG.messageSelector).length === 0) {
        afficherMessageAttente();
      }

      // Mettre à jour le message
      mettreAJourMessageAttente(
        "🔮 Consultation de l'oracle...",
        "Interprétation des cartes en cours"
      );

      const response = event.detail.apiResponse;

      // Vérifier si Make a renvoyé directement l'URL dans la réponse
      if (response && response.tirage_url) {
        console.log("URL de tirage reçue directement :", response.tirage_url);

        mettreAJourMessageAttente(
          "✨ Votre tirage est prêt !",
          "Redirection en cours..."
        );

        setTimeout(() => {
          window.location.href = response.tirage_url;
        }, 1500);
        return;
      }

      // Sinon, utiliser l'ID du post pour vérifier périodiquement
      if (response && response.post_id) {
        console.log("Post ID trouvé dans la réponse CF7 :", response.post_id);
        verifierTirageURL(response.post_id);
        return;
      }

      // Fallback : récupérer le dernier post créé
      mettreAJourMessageAttente(
        "🔍 Recherche de votre tirage...",
        "Localisation en cours"
      );

      setTimeout(() => {
        recupererDernierPost()
          .done(function (response) {
            if (response.success && response.data.post_id) {
              console.log("Dernier post trouvé :", response.data.post_id);
              verifierTirageURL(response.data.post_id);
            } else {
              console.error("Impossible de trouver un post");
              afficherErreur("Impossible de localiser votre tirage.");
            }
          })
          .fail(function () {
            console.error("Erreur lors de la récupération du dernier post");
            afficherErreur("Erreur de connexion. Veuillez réessayer.");
          });
      }, 2000);
    },
    false
  );

  // Gestionnaire d'erreur pour les formulaires CF7
  document.addEventListener(
    "wpcf7mailfailed",
    function (event) {
      // Vérifier si c'est le bon formulaire
      if (!estBonFormulaire(event)) {
        return;
      }

      console.error("Échec de l'envoi du formulaire CF7 ciblé");
      afficherErreur(
        "Erreur lors de l'envoi du formulaire. Veuillez réessayer."
      );
    },
    false
  );

  // Gestionnaire pour les erreurs de validation
  document.addEventListener(
    "wpcf7invalid",
    function (event) {
      // Vérifier si c'est le bon formulaire
      if (!estBonFormulaire(event)) {
        return;
      }

      console.log("Erreur de validation du formulaire CF7 ciblé");
      masquerMessageAttente();
    },
    false
  );

  // Gestionnaire pour les erreurs de spam
  document.addEventListener(
    "wpcf7spam",
    function (event) {
      // Vérifier si c'est le bon formulaire
      if (!estBonFormulaire(event)) {
        return;
      }

      console.log("Formulaire ciblé détecté comme spam");
      masquerMessageAttente();
    },
    false
  );

  // Empêcher la soumission multiple pour le formulaire spécifique
  let formSubmitted = false;

  $(CONFIG.formSelector).on("submit", function (e) {
    if (formSubmitted) {
      e.preventDefault();
      return false;
    }
    formSubmitted = true;

    // Réinitialiser après 30 secondes (sécurité)
    setTimeout(() => {
      formSubmitted = false;
    }, 30000);
  });

  // Log pour confirmer l'initialisation
  console.log(
    "Script tarot initialisé pour le formulaire:",
    CONFIG.formSelector
  );

  // Fonction utilitaire pour déboguer
  window.debugTarot = {
    afficherMessage: afficherMessageAttente,
    masquerMessage: masquerMessageAttente,
    mettreAJour: mettreAJourMessageAttente,
    verifierURL: verifierTirageURL,
    config: CONFIG,
    formExists: $(CONFIG.formSelector).length > 0,
  };
});
