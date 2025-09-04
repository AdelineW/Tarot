// Scroll -> https://github.com/KoryNunn/scroll-into-view
(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
  var COMPLETE="complete",CANCELED="canceled";function raf(e){if("requestAnimationFrame"in window)return window.requestAnimationFrame(e);setTimeout(e,16)}function setElementScroll(e,t,n){e.self===e?e.scrollTo(t,n):(e.scrollLeft=t,e.scrollTop=n)}function getTargetScrollLocation(e,t){var n,l,i,o,r,a,s,f=e.align,c=e.target.getBoundingClientRect(),d=f&&null!=f.left?f.left:.5,u=f&&null!=f.top?f.top:.5,g=f&&null!=f.leftOffset?f.leftOffset:0,m=f&&null!=f.topOffset?f.topOffset:0,h=d,p=u;if(e.isWindow(t))a=Math.min(c.width,t.innerWidth),s=Math.min(c.height,t.innerHeight),l=c.left+t.pageXOffset-t.innerWidth*h+a*h,i=c.top+t.pageYOffset-t.innerHeight*p+s*p,i-=m,o=(l-=g)-t.pageXOffset,r=i-t.pageYOffset;else{a=c.width,s=c.height,n=t.getBoundingClientRect();var E=c.left-(n.left-t.scrollLeft),S=c.top-(n.top-t.scrollTop);l=E+a*h-t.clientWidth*h,i=S+s*p-t.clientHeight*p,l-=g,i-=m,l=Math.max(Math.min(l,t.scrollWidth-t.clientWidth),0),i=Math.max(Math.min(i,t.scrollHeight-t.clientHeight),0),o=l-t.scrollLeft,r=i-t.scrollTop}return{x:l,y:i,differenceX:o,differenceY:r}}function animate(e){var t=e._scrollSettings;if(t){var n=t.maxSynchronousAlignments,l=getTargetScrollLocation(t,e),i=Date.now()-t.startTime,o=Math.min(1/t.time*i,1);if(t.endIterations>=n)return setElementScroll(e,l.x,l.y),e._scrollSettings=null,t.end(COMPLETE);var r=1-t.ease(o);if(setElementScroll(e,l.x-l.differenceX*r,l.y-l.differenceY*r),i>=t.time)return t.endIterations++,animate(e);raf(animate.bind(null,e))}}function defaultIsWindow(e){return e.self===e}function transitionScrollTo(e,t,n,l){var i,o=!t._scrollSettings,r=t._scrollSettings,a=Date.now(),s={passive:!0};function f(e){t._scrollSettings=null,t.parentElement&&t.parentElement._scrollSettings&&t.parentElement._scrollSettings.end(e),n.debug&&console.log("Scrolling ended with type",e,"for",t),l(e),i&&(t.removeEventListener("touchstart",i,s),t.removeEventListener("wheel",i,s))}r&&r.end(CANCELED);var c=n.maxSynchronousAlignments;return null==c&&(c=3),t._scrollSettings={startTime:a,endIterations:0,target:e,time:n.time,ease:n.ease,align:n.align,isWindow:n.isWindow||defaultIsWindow,maxSynchronousAlignments:c,end:f},"cancellable"in n&&!n.cancellable||(i=f.bind(null,CANCELED),t.addEventListener("touchstart",i,s),t.addEventListener("wheel",i,s)),o&&animate(t),i}function defaultIsScrollable(e){return"pageXOffset"in e||(e.scrollHeight!==e.clientHeight||e.scrollWidth!==e.clientWidth)&&"hidden"!==getComputedStyle(e).overflow}function defaultValidTarget(){return!0}function findParentElement(e){if(e.assignedSlot)return findParentElement(e.assignedSlot);if(e.parentElement)return"BODY"===e.parentElement.tagName?e.parentElement.ownerDocument.defaultView||e.parentElement.ownerDocument.ownerWindow:e.parentElement;if(e.getRootNode){var t=e.getRootNode();if(11===t.nodeType)return t.host}}module.exports=function(e,t,n){if(e){"function"==typeof t&&(n=t,t=null),t||(t={}),t.time=isNaN(t.time)?1e3:t.time,t.ease=t.ease||function(e){return 1-Math.pow(1-e,e/2)};var l,i=findParentElement(e),o=1,r=t.validTarget||defaultValidTarget,a=t.isScrollable;for(t.debug&&(console.log("About to scroll to",e),i||console.error("Target did not have a parent, is it mounted in the DOM?"));i;)if(t.debug&&console.log("Scrolling parent node",i),r(i,o)&&(a?a(i,defaultIsScrollable):defaultIsScrollable(i))&&(o++,l=transitionScrollTo(e,i,t,s)),!(i=findParentElement(i))){s(COMPLETE);break}return l}function s(e){--o||n&&n(e)}};
  },{}],2:[function(require,module,exports){
  window.scrollIntoView=require("./scrollIntoView");
  },{"./scrollIntoView":1}]},{},[2]);

  
  window.addEventListener("load", function() {

    let tkna_preloader = document.getElementById('tkna_preloader');
    tkna_preloader.style.opacity=1;
    let nametarot = tkna_preloader.getAttribute('data-nametarot');
    let tablero = document.getElementById('tablero');
    let text_spread = document.getElementById('text_spread');
    let deck = document.getElementById('d0');
    let btnResult = document.getElementById('btnResult');
    let readTarot = document.getElementById('readTarot');
    let backface = document.getElementsByClassName('backface');
    let backfaceArr = Array.from(backface);
    let marcador = document.getElementsByClassName('marcador');
    let card = document.getElementsByClassName('card');

    let numero_spread = tkna_preloader.getAttribute('data-n');
    let numCartas = 0;
    let count = 0

    if (numero_spread > 3) {
      let myCard = atob(ajax_tarot.myCard);
      let div = document.createElement('div');
      div.innerHTML = myCard;
      let subTitle = document.getElementById('subTitle');
      ajax_tarot.wp_content != atob('dmFsaWQ=') ? subTitle.appendChild(div): '';
    }


    // orden
    let dataOrden = document.getElementById('tablero').getAttribute('data-orden');
    let dataNoComillas = dataOrden.split(','); 
    let clickOrden = [];

    let opT = document.getElementsByClassName('opT');
    let barajar = document.getElementById('barajar');
    let IdsPost = [];
    let flip_arr = [];


    dataNoComillas.forEach(element => {
        clickOrden.push(parseFloat(element));
    });


  
        // Efectos en la baraja
        let tl = anime.timeline({
          duration : 750,
          autoplay: false
        });

        tl.add({

          targets: '.backface',
          translateX: anime.stagger(100 ,{grid:[backfaceArr.length,1],from:'last'}),
          //boxShadow: '0px 0px rgba(255,255,255,0)',
          easing: function(el, i, total) {
            return function(t) {
              return Math.pow(Math.sin(t * (i + 1)), total);
            }
          }
        })
        .add({
          targets: '.backface',
          translateX: anime.stagger(-100 ,{grid:[backfaceArr.length,1],from:'first'}),
          easing: function(el, i, total) {
            return function(t) {
              return Math.pow(Math.sin(t * (i + 1)), total);
            }
          }
        })
        .add({
          targets: '.backface',
          translateX: anime.stagger(0 ,{grid:[backfaceArr.length,1],from:'last'}),
          //delay: anime.stagger(1 ,{grid:[backfaceArr.length,1],from:'center'}),
          easing: 'easeInOutSine'
          //rotate: anime.stagger('0turn'),
        });

        if(barajar !== null){barajar.onclick = tl.play}

   
        // Click en botón empezar
        if (readTarot !== null) {
            readTarot.addEventListener('click', function(){
              deck.style.display="block"
              btnResult.style.display="none"

              setTimeout(() => {
                text_spread.style.visibility="hidden";
                anime({
                    targets: '#'+text_spread.getAttribute('id'),
                    height:  '0px',
                    easing: 'easeInOutQuad'
                });

                // desplazar la baraja al tablero
                scrollIntoView(tkna_preloader, {
                  time: 500,
                align:{top: 0,topOffset: 0}
                });

              }, 300);
          });
        }


  

     //////////////// Bucle Cartas
     /////////////////////////////////////
    backfaceArr.forEach(function(back){
      let backface_ID = backface[numCartas].getAttribute('id');
      let img_ID = card[numCartas].getAttribute('id');
      let marcador_ID = marcador[numCartas];
          back.addEventListener('click', ClickCard);
            function ClickCard(e){
              e.preventDefault();
              let flip = back.getAttribute('data-flip');
              count += 1;

              if (count == 1) {

                setTimeout(() => {
                  text_spread.style.visibility="hidden";
                  anime({
                      targets: '#'+text_spread.getAttribute('id'),
                      height:  '0px',
                      easing: 'easeInOutQuad'
                  });
        
                  // desplazar la baraja al tablero
                  scrollIntoView(tkna_preloader, {
                    time: 500,
                   align:{top: 0,topOffset: 0}
                  });
        
                }, 300);
                
              }

              //  Click menor que total
              if (count <= numero_spread) {
                document.getElementById('TitleSelect2').innerHTML=ajax_tarot.refe[count];

                  marcador_ID.innerHTML=count;
                  marcador_ID.style.display='flex';
                  anime({
                    targets: '#'+backface_ID,
                    delay: 600,
                    update: function() {
                      document.getElementById(backface_ID).style.boxShadow="none";
                      document.getElementById(img_ID).classList.add("flash");
                      document.getElementById(backface_ID).style.background='initial';
                    }
                  });
                      

                  // arrays para pasar por Ajax al archivo php
                  IdsPost.push(back.getAttribute('data-id'));
                  flip_arr.push(flip);





                  // Click en cartas y el orden
                  let img_src = document.getElementById(img_ID).getAttribute('data-full');
                  const img = document.createElement('img');
                  img.id="m"+count+"_img";
                  img.className="cardClik";
                  img.setAttribute('src', img_src)
                  document.getElementById('pic'+clickOrden[count-1]).appendChild(img);
                  if (flip == 'flip1') {
                    document.getElementById("m"+count+"_img").classList.add("flip1");
                  }

                  // Añadir y quitar clases
                  back.removeEventListener("click", ClickCard);
                  back.classList.add("c_block");
                  back.classList.remove("c_select");
                  
                  // Mostrar carta en el spread
                  anime({
                      targets: "#m"+count+"_img",
                      delay: 500,
                      opacity: 1,
                      duration: 1000,
                    });


              }// Fin menor que total

              //  Click Penúltima carta
              if (count == numero_spread -1) {
                let TitleCustom = document.getElementById('TitleCustom');
                if(TitleCustom !== null){TitleCustom.style.display='none'}
                document.getElementById('TitleSelect2').style.display='flex';
              }

              //  Click Última carta
              if (count == numero_spread) {
                document.getElementById('TitleSelect2').innerHTML='&nbsp;';

                setTimeout(() => {
                  bloq_deck.style.display='none';
                  btnResult.style.display='none';
                  scrollIntoView(tkna_preloader, {
                    time: 500,
                    align:{top: 0,topOffset: 30}
                  });

                  opT[0].style.opacity= 0.2;
                  opT[1].style.opacity= 0.2;
                  tablero.style.opacity= 0.2;
                  text_spread.style.display='none';
                  document.getElementById('loader_tkna-wrapper').style.display='block';


                    setTimeout(() => {

                    opT[0].style.opacity= 1;
                    opT[1].style.opacity= 1;
                    tablero.style.opacity= 1;
                    text_spread.style.display='none';
                    document.getElementById('loader_tkna-wrapper').style.display='none';
                    
                    let cont_result = document.getElementById('cont_result');
                    cont_result.style.display='block';

                  }, 3300);

/*                   jQuery(document).ready(function ($) {
                    jQuery.ajax({
                        type: "post",
                        url: ajax_tarot.url,
                        data: "action=" + ajax_tarot.action + "&nonce=" + ajax_tarot.nonce + "&cards="+ IdsPost + "&nametarot="+ nametarot + "&flip="+ flip_arr,
                        success: function(result){
                            $('#results').html(result);
                        }
                    });
                  });
 */

jQuery(document).ready(function($){

  // 1) Fonction utilitaire pour récupérer le nom et l'état retourné de chaque carte sélectionnée
  function getSelectedCards() {
    var cards = [];
    
    // ADAPTEZ le sélecteur selon la structure HTML réelle de vos cartes sélectionnées
    $('.cardClik[style*="opacity: 1"]').each(function(){
      var imgSrc = $(this).attr('src');
      var cardName = extractCardNameFromUrl(imgSrc);
      var isReversed = $(this).hasClass('flip1'); // ou autre indicateur de renversement
      
      if(cardName) {
        cards.push({ name: cardName, flip: isReversed ? 1 : 0 });
      }
    });
    return cards;
  }
  
  // 2) Fonction pour extraire le nom de la carte depuis l'URL
  function extractCardNameFromUrl(url) {
    if(!url) return '';
    
    // Exemple: "https://tarot.reactive-com.com/wp-content/uploads/2025/07/16-La-Tour.png"
    // Extraire "16-La-Tour.png"
    var filename = url.split('/').pop();
    
    // Enlever l'extension .png
    var nameWithNumber = filename.replace('.png', '');
    
    // Enlever le numéro et le tiret initial (ex: "16-La-Tour" → "La-Tour")
    var cardName = nameWithNumber.replace(/^\d+-/, '');
    
    // Remplacer les tirets par des espaces (ex: "La-Tour" → "La Tour")
    cardName = cardName.replace(/-/g, ' ');
    
    return cardName;
  }

  // 3) Handler click sur le bouton Dust
  $('#dust-submit').on('click', function(e){
    e.preventDefault();
    var sel = getSelectedCards();
    if(sel.length === 0){
      alert('Veuillez d\'abord tirer vos cartes.');
      return;
    }
    
    // Récupération de la question saisie dans le formulaire
    var userQuestion = $('#dust-question').val();
    if(!userQuestion || userQuestion.trim() === ''){
      alert('Veuillez saisir votre question.');
      return;
    }
    
    // Préparez les données pour correspondre au format PHP attendu
    var cardNames = sel.map(function(c){ return c.name; }); // Tableau de noms
    var flipArr   = sel.map(function(c){ return c.flip; }).join(',');
    
    var payload = {
      action: ajax_tarot.action,
      nonce:  ajax_tarot.nonce,
      question: userQuestion,
      cards: cardNames,              // Ex: ["La Tour", "L Imperatrice", "Le Pape"]
      flip: flipArr,                 // Ex: "0,1,0"
      nametarot: ajax_tarot.nametarot
    };
    
    // 4) Lancement de l'AJAX vers WordPress
    $.post(ajax_tarot.url, payload, function(response){
      $('#results').html(response.data || response);
    });
  });

});


                }, 3300);





              }


            }// Fin ClickCard
        numCartas ++;

    });// Fin Bucle Cartas

////////// FIN Window
});