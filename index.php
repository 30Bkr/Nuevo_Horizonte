<?php
include_once('./global/utils.php');
include_once('./layout/layaout1.php');
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <h1>Dashboard del administrador</h1>
      </div>
    </div>
  </div>

</div>


<?php
include_once('./layout/layaout2.php')
?>

<!-- ==================================================      Tablas de base de datos:    =================================================================-->

<!-- Agregar tabla de utilidades o globales para cambiar el nombre de la institucion, el logo, minimo y maximo de edad de estudiantes, etc -->

<!-- ==================================================      Tablas maestras:    =================================================================-->

<!-- Tablas de edicion de la tabla globales  -->
<!-- hacer tabla de inicio del periodo escolar  -->




<!-- ==================================================      Inscripcion:  -->

<!-- Agregar especiales a la tabla de especialidades (ingles, mates, etc) -->
<!-- Agregar validacion en el apartado de re-inscripciones para validar que el estudiante solo pueda ser inscrito en 1 solo periodo academico -->
<!-- Validar antes de inscribir que 1 estudiante pueda estar inscrito solo si hay cupos disponibles  -->
<!-- Agregar y traer en la fecha de nacimiento de los estudiantes el max y min de edad que pueden tener directamente de la nueva tabla de utilidades o globales que se crearan en la base de datos  -->

<!-- ==================================================      Cursos:    =================================================================-->

<!-- Agregar en las listas de las secciones un boton que solicita ficha tecnica del estudiante (debe ser solo visible, no editable) -->
<!-- Agregar en las listas de las secciones un boton que solicita ficha tecnica del representante (debe ser solo visible, no editable) -->
<!-- En el apartado de edición de grado, el grado y la seccion solo deben ser visibles y no editables (LIIIISTO) --->
<!-- En el apartado de grado, agregar para crear nuevos grados y secciones---->
<!-- En el apartado de edición de grado, la capacidad no puede ser menor a la de los estudiantes ya registrados---->
<!-- En el apartado de edición de grado, la capacidad no puede ser menor a la de los estudiantes ya registrados---->
<!-- En la ficha tecnica del representante existe un boton de actualizar data--->
<!-- En la ficha tecnica del estudiante existe un boton de actualizar data--->
<!-- En el apartado de edición de grado, el grado y la seccion solo deben ser visibles y no editables (LIIIISTO) --->
<!-- En el apartado de grado, agregar para crear nuevos grados y secciones (LIIIIISTO)---->
<!-- En el apartado de edición de grado, la capacidad no puede ser menor a la de los estudiantes ya registrados (LIIIIIISTO)---->
<!-- Quitar de la lista de estudiantes la accion de eliminar (LIIIIIISTO)--->
<!-- En en el reporte de pdf corregir la numeración (LIIIIISTO)--->
<!-- En en el reporte de pdf de estudiantes se deben respetar los margenes, e intercambiar el orden del listado y estadisticas (LIIISTO)--->


<!-- ==================================================      Docentes:    =================================================================-->
<!-- El usuario se debe crear automaticamente con la CI.  -->
<!-- Terminar de agregar todos los campos del formulario y quitar la edicion del usuario.  -->
<!-- cambiar el boton de borrar a tipo switch una vaina asi (habilitar e inhabilitar) -->

























<!-- ==================================================     historial:    =================================================================-->
<!-- crea una tabla de historial en donde guarde todo lo que se modifica, inserta o edita, quien lo realizo. y    que fue lo que edito -->