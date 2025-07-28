document.addEventListener("DOMContentLoaded", () => {

  console.log("tarokina_con.js");
  const { __, _e, _n, sprintf } = wp.i18n;
  const license = tarokina_con.license; // Objeto con la licencia
  const license_name = license.license_name; // Nombre único de la licencia
  const ajaxUrl = tarokina_con.ajax_url || admin_url;
  const security = tarokina_con.security;
  const id_form = tarokina_con.id_form;

  console.log("license tarokina: ", license_name);

  // Actualizamos los avisos si previamente se guardaron en localStorage (para soportar recarga de página)
  const infoDiv = document.getElementById(`${license_name}-info_lincese`);
  const badge = document.getElementById(`${license_name}-status`);
  const icon = document.getElementById(`${license_name}-icon`);
  const storedAlert = localStorage.getItem(`tarokina_alert_${license_name}`);
  if (storedAlert) {
    const alertData = JSON.parse(storedAlert);
    if (infoDiv) {
      infoDiv.className = `alert ${alertData.infoClass} tarokina-notice`; // Añadir la clase tarokina-notice
      // Añadir el botón de cierre manualmente
      infoDiv.innerHTML = `${alertData.message}`;
      
    }
    if (badge) {
      badge.textContent = alertData.badgeText;
      badge.className = alertData.badgeClass;
    }
    if (icon && alertData.iconClass) {
      icon.className = `input-group-text ${alertData.iconClass}`;
    }
    localStorage.removeItem(`tarokina_alert_${license_name}`);
  }

  // Usamos license_name para construir las clases de los elementos
  const buttonClass = `${license_name}-check`;
  const buttonLoader = `${license_name}-loader`;
  const buttonRepeat = `${license_name}-repeat`;
  const buttons = document.getElementsByClassName(buttonClass);
  const iconCheckLoader = document.getElementById(buttonLoader);
  const iconCheckRepeat = document.getElementById(buttonRepeat);

  // Establecer estado inicial
  if (iconCheckLoader) {
    iconCheckLoader.style.display = 'none';
  }
  if (iconCheckRepeat) {
    iconCheckRepeat.style.display = 'block';
  }
  
  if (buttons.length > 0) {
    Array.from(buttons).forEach(btn => {
      btn.addEventListener("click", async (e) => {
        e.preventDefault();
        
        // Obtener la acción del botón pulsado (activate_license o deactivate_license)
        const action = btn.getAttribute('data-action');
        
        // Obtener la clave de licencia del input asociado
        const licenseInput = document.getElementById(license_name);
        const licenseKey = licenseInput ? licenseInput.value : "";

        try {
          // Mostrar indicador de carga
          btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + 
                         (action === 'deactivate_license' ? tarokina_con.message.deactivating : tarokina_con.message.activating);
          btn.disabled = true;

          const response = await fetch(ajaxUrl, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: new URLSearchParams({
              action: "handle_ajax_tarokina_con",
              transient_name: license_name,
              license_key: licenseKey,
              activation_action: action, // Enviar la acción específica (activate_license o deactivate_license)
              security: security
            })
          });

          const result = await response.json();
          
          if (result.success) {
            // Definir variables para badge, icono y alerta según el estado recibido
            let badgeText, infoClass, iconClass, badgeClass;
            if (result.data.new_status === "valid" || result.data.new_status === "active") {
              badgeText = tarokina_con.message.pluginAtivated;
              infoClass = "alert alert-success alert-dismissible";
              iconClass = "text-bg-success";
              badgeClass = "fw-medium text-success";
            } else {
              badgeText = tarokina_con.message.pluginDeactivated;
              iconClass = "text-bg-danger";
              badgeClass = "fw-medium text-danger";
              // Se asigna la clase de alerta basándose en error_code
              switch (result.data.error) {
                case "expired":
                case "disabled":
                case "revoked":
                case "missing":
                case "item_name_mismatch":
                case "no_activations_left":
                  infoClass = "alert alert-danger alert-dismissible";
                  break;
                default:
                  // Verificar si la acción fue deactivate_license
                  infoClass = (action === 'deactivate_license') 
                    ? "alert alert-danger alert-dismissible" 
                    : "alert alert-info alert-dismissible";
              }
            }

            // Actualizar los elementos en la página
            if (badge) {
              badge.textContent = badgeText;
              badge.className = badgeClass;
            }
            if (icon) {
              icon.className = `input-group-text ${iconClass}`;
            }
            if (infoDiv) {
              infoDiv.className = `alert ${infoClass} tarokina-notice`; // Añadir la clase tarokina-notice
              // Se usa innerHTML para permitir la inserción y renderizado del html
              // Reemplazar con clases de WordPress para el botón de cierre
              infoDiv.innerHTML = `${result.data.message}`;
              
            }
            // Almacenar en localStorage para persistir el mensaje tras la recarga de la página
            localStorage.setItem(`tarokina_alert_${license_name}`, JSON.stringify({
              message: result.data.message, // Sin incluir el botón para evitar duplicados
              infoClass: infoClass,
              badgeText: badgeText,
              iconClass: iconClass,
              badgeClass: badgeClass
            }));

            // Actualizar el botón según el nuevo estado
            if (result.data.new_status === "valid" || result.data.new_status === "active") {
              btn.innerHTML = 'Deactivate';
              btn.setAttribute('data-action', 'deactivate_license');
              btn.classList.remove('btn-primary');
              btn.classList.add('btn-danger');
            } else {
              btn.innerHTML = 'Activate';
              btn.setAttribute('data-action', 'activate_license');
              btn.classList.remove('btn-danger');
              btn.classList.add('btn-primary');
            }
            btn.disabled = false;

            // Encuentra el formulario y lo envía de forma normal
            const form = document.getElementById(id_form);
            if (form && form.tagName === "FORM") {
              // Usar HTMLFormElement.prototype.submit.call evita el problema de nombres conflictivos
              setTimeout(() => {
                // Crear y disparar un evento de clic en el botón de submit
                const submitButton = form.querySelector('input[type="submit"]');
                if (submitButton) {
                  submitButton.click();
                } else {
                  // Si no hay botón visible, utilizamos el método nativo de envío
                  HTMLFormElement.prototype.submit.call(form);
                }
              }, 100);
            } else {
              console.error(__("Form not found", "tarokina"));
            }
          } else {
            console.error(`Error: ${result.data}`);
            // Restablecer el botón en caso de error
            btn.innerHTML = action === 'deactivate_license' ? 'Deactivate' : 'Activate';
            btn.disabled = false;
          }

        } catch (error) {
          console.error("Error en la petición:", error);
          // Restablecer el botón en caso de error
          btn.innerHTML = action === 'deactivate_license' ? 'Deactivate' : 'Activate';
          btn.disabled = false;
        }
      });
    });
  }
});
