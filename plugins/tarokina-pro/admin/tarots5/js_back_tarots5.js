window.addEventListener("load", function () {
  function PrimeraMayuscula(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function empty(data) {
    if (typeof data == "number" || typeof data == "boolean") {
      return false;
    }
    if (typeof data == "undefined" || data === null) {
      return true;
    }
    if (typeof data.length != "undefined") {
      return data.length == 0;
    }
    var count = 0;
    for (var i in data) {
      if (data.hasOwnProperty(i)) {
        count++;
      }
    }
    return count == 0;
  }

  // Tabs Body
  let complex_name = document.getElementsByClassName("cf-complex__group-body");
  let text_holder = document.getElementById("text_holder").innerText;
  let complex_placeholder = document.getElementsByClassName(
    "cf-complex__placeholder-label"
  );
  let wp_content = back_tarots5.wp_content;

  let addons_inactive = back_tarots5.addons_inactive;
  let Arr_inactive = Object.values(addons_inactive);

  if (!empty(complex_placeholder)) {
    complex_placeholder[0].innerHTML = text_holder;
  }

  if (wp_content["edd_restriction_tarokina"] != atob("dmFsaWQ=")) {
    let shopId = document.getElementsByClassName("shopId");
    const Arr_shopId = Array.from(shopId);
    Arr_shopId.forEach(function (id) {
      id.classList.add("deactivate");
      //id.style.opacity="1"
    });
  }

  const tabs = document.getElementsByClassName("cf-complex__group");
  for (var i = 0; i < tabs.length; i++) {
    let Tarot_key = document.getElementsByName(
      "carbon_fields_compact_input[_tarokki_tarot_complex5][" + i + "][value]"
    );
    let Tarot_ID = document.getElementsByName(
      "carbon_fields_compact_input[_tarokki_tarot_complex5][" +
        i +
        "][_tkta_id]"
    );

    if (Tarot_ID[0].value == 99999999) {
      complex_name[i].classList.add("demo");
    }

    let inputValue = Tarot_key[0].defaultValue;
    let find_inactive = Arr_inactive.find((element) => element == inputValue);

    if (
      wp_content[inputValue] != atob("dmFsaWQ=") &&
      inputValue !== "tarokina-pro"
    ) {
      complex_name[i].classList.add("inv");
    } else {
      complex_name[i].classList.add("val");
    }

    if (find_inactive !== undefined) {
      complex_name[i].classList.add("PlgDis");
    } else {
      complex_name[i].classList.add("PlgEna");
    }
  }

  // POPUP LIST IN ADD BUTTON
  const complex_List = document.getElementsByClassName(
    "cf-complex__inserter-item"
  );
  const Arr_complex_List = Array.from(complex_List);
  const bloqTabs = document.getElementsByClassName("cf-complex__groups")[0];

  Arr_complex_List.forEach(function (list) {
    list.addEventListener("click", function () {
      setTimeout(() => {
        if (wp_content["edd_restriction_tarokina"] != atob("dmFsaWQ=")) {
          let lastChild = bloqTabs.lastElementChild;
          lastChild.classList.add("inv");
        }
      }, 500);
    });

    let nameList = list.innerHTML;
    const url = "../wp-content/plugins/tarokki-" + nameList + "/data.json";

    if (list.textContent !== "Tarokina") {
      fetch(url)
        .then((respuesta) => respuesta.json())
        .then((resultado) => {
          let typeJson = resultado[nameList].tarot_type;
          list.innerHTML = PrimeraMayuscula(nameList.split("-").join(" "));
          list.className += " " + typeJson;

          if (typeJson != "free" && wp_content[nameList] != atob("dmFsaWQ=")) {
            list.className += " inactive";
          }
        });
    }
  });

  // Boton cancelar al borrar
  const cancel_item = document.getElementById("cancelSib");
  cancel_item.addEventListener("click", function (e) {
    e.preventDefault();
    window.location.reload();
  });

  // activar borrar items - Fields complex
  const btn_guardar = document.getElementById("publish");
  btn_guardar.value = back_tarots5.save_text;
  const del_tarots = document.querySelector("#del_tarots");

  let trash = document.getElementsByClassName("dashicons-trash");
  let infoDelete = this.document.getElementById("infoDelete");
  const trashArr = Array.from(trash);

  del_tarots.addEventListener("click", function (e) {
    e.preventDefault();
    del_tarots.style.display = "none";

    trashArr.forEach(function (trashX) {
      trashX.style.display = "block";
    });
  });

  trashArr.forEach(function (trashX) {
    trashX.addEventListener("click", function (e) {
      trashX.style.display = "block";
      cancel_item.style.display = "block";
      btn_guardar.style.background = "#ff5656";
      btn_guardar.value = back_tarots5.textDelete;
      infoDelete.style.display = "block";
    });
  });

  // Ocultar las papeleras
  let pape2 = document.getElementsByClassName(
    "cf-complex__group-actions--grid"
  );
  const papeArr2 = Array.from(pape2);

  papeArr2.forEach(function (papeX2) {
    papeX2.addEventListener("click", function () {
      let trash2 = document.getElementsByClassName("dashicons-trash");
      const trashArr2 = Array.from(trash2);
      trashArr2.forEach(function (trash2X) {
        trash2X.style.display = "none";
      });
    });
  });

  //////////////////////// Mostrar shortcode ///////////////////////
  let short = document.getElementsByClassName("short");
  const Array_short = Array.from(short);
  let num = 0;
  Array_short.forEach(function (nameValue) {
    const position =
      "carbon_fields_compact_input[_tarokki_tarot_complex5][" +
      num +
      "][_tkta_name]";

    try {
      const name = document.getElementsByName(position);
      const names = name[0].value.toLowerCase();
      const cleanedName = sanitizeForShortcode(names);
      const values = `[tarot_pro name="${cleanedName}"]`;
      nameValue.value = values;

      if (names.user && names.user.value && names.user.value.length >= 1) {
        name.value = "";
      } else {
        name.value = position;
      }
    } catch (e) {
      // Manejo silencioso de errores para evitar interrupciones
    }
    num++;
  });

  /**
   * Sanitiza texto para uso en shortcodes, eliminando acentos y caracteres especiales
   * Convierte texto como "Odin's Magical Oracle" en "odins_magical_oracle"
   * o "Je préfère le thé au café" en "je_prefere_le_the_au_cafe"
   *
   * @param {string} text - Texto a sanitizar
   * @returns {string} - Texto sanitizado para shortcode
   */
  function sanitizeForShortcode(text) {
    if (!text || typeof text !== "string") {
      return "";
    }

    return text
      .toLowerCase() // Convertir a minúsculas
      .normalize("NFD") // Normalizar para separar caracteres base de acentos
      .replace(/[\u0300-\u036f]/g, "") // Eliminar marcas diacríticas (acentos)
      .replace(/[^a-z0-9\s]/g, "") // Eliminar todo excepto letras, números y espacios
      .trim() // Eliminar espacios al inicio y final
      .replace(/\s+/g, "_"); // Reemplazar espacios (uno o más) con guión bajo
  }
  /////////////////////////////////////////////////////////////////////////
  // Fin Create shortcode

  let bloq_tarjeta = document.getElementById("bloq_tarjeta");
  bloq_tarjeta.style.display = "grid";

  // Free install
  const spinner_free = document.getElementById("spinner_free");
  const freeinstall = document.getElementById("freeinstall");
  if (freeinstall !== null) {
    freeinstall.addEventListener("click", function () {
      freeinstall.style.display = "none";
      spinner_free.style.display = "block";
    });
  }

  // Free close
  const Freeclose = document.getElementById("Freeclose");
  if (Freeclose !== null) {
    Freeclose.addEventListener("click", function () {
      Freeclose.style.display = "none";
      spinner_free.style.display = "block";
    });
  }

  // Demo install
  const spinner_demo = document.getElementById("spinner_demo");
  const demoinstall = document.getElementById("demoinstall");
  if (demoinstall !== null) {
    demoinstall.addEventListener("click", function () {
      demoinstall.style.display = "none";
      spinner_demo.style.display = "block";
    });
  }

  // Demo close
  const democlose = document.getElementById("democlose");
  if (democlose !== null) {
    democlose.addEventListener("click", function () {
      democlose.style.display = "none";
      spinner_demo.style.display = "block";
    });
  }

  // quitar el botón de añadir tarot
  const sub_menu = document.querySelector(".cf-complex__inserter-menu");
  if (sub_menu !== null) {
    sub_menu.addEventListener("click", function () {
      setTimeout(() => {
        const btn_add = document.querySelector(".cf-complex__inserter-button");
        if (btn_add !== null) {
          btn_add.style.display = "none";
        }
      }, 2000);
    });
  }
});
// window
