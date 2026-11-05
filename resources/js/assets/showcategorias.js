document.addEventListener('DOMContentLoaded', function () {
    const modalShow = document.getElementById('modalShowCategoria');
    if (!modalShow) return;

    modalShow.addEventListener('show.bs.modal', function (event) {
        // Botón que disparó el modal
        const button = event.relatedTarget;

        // Extraer los datos de los atributos data-*
        const codigo = button.getAttribute('data-codigo');
        const nombre = button.getAttribute('data-nombre');
        const descripcion = button.getAttribute('data-descripcion');
        const padre = button.getAttribute('data-padre');
        const fecha = button.getAttribute('data-fecha');
        const usuario = button.getAttribute('data-usuario');
        const motivo = button.getAttribute('data-motivo');

        // Insertar los valores en el modal
        document.getElementById('show_codigo').textContent = codigo;
        document.getElementById('show_nombre').textContent = nombre;
        document.getElementById('show_descripcion').textContent = descripcion;
        document.getElementById('show_padre').textContent = padre;
        document.getElementById('show_fecha').textContent = fecha;
        document.getElementById('show_usuario').textContent = usuario;
        document.getElementById('show_motivo').textContent = motivo;
    });
});