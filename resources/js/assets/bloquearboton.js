window.bloquearBoton = function(form) {
    const boton = form.querySelector('button[type="submit"]');
    boton.disabled = true; // Desactiva el botón
    boton.innerText = 'Procesando...'; // Cambia el texto
}