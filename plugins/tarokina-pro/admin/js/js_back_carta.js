window.addEventListener("load", function () {
  // change checbox for radio in Decks
  let inputDeck = document.querySelectorAll(".selectit input");
  inputDeck.forEach(function (radioDeck) {
    radioDeck.type = "radio";
  });

  // Inyectar el nombre de la categoría en la lista
  let = deckname = document.getElementById("cat_name");
  if (deckname !== null) {
    deckname.innerHTML;
  } else {
    deckname = "";
  }

  let = decknameLi = document.getElementsByClassName("decknameLi");
  let = Array_decknameLi = Array.from(decknameLi);

  let = Array_decknameLi = Array.from(decknameLi);
  Array_decknameLi.forEach(function (name) {
    name.innerHTML = deckname.innerHTML;
  });


});
// window
