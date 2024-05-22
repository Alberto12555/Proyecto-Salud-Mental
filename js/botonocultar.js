document.addEventListener('DOMContentLoaded', function () {
  let elementosLista = document.querySelectorAll('li');

  for (let i = 0; i < elementosLista.length; i++) {
    elementosLista[i].addEventListener('click', function () {
      let informacion = this.querySelector('.informacion');
      if (informacion.style.display === 'none') {
        informacion.style.display = 'block';
      } else {
        informacion.style.display = 'none';
      }
    });
  }
});