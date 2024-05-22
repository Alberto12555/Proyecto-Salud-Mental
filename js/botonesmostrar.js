let botonesMostrar = document.querySelectorAll('.boton-mostrar');

for (let i = 0; i < botonesMostrar.length; i++) {
    botonesMostrar[i].addEventListener('click', function () {
        let informacion = this.previousElementSibling;
        if (informacion.style.display === 'none') {
            informacion.style.display = 'block';
        } else {
            informacion.style.display = 'none';
        }
    });
}