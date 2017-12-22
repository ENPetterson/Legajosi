create or replace view v_formulario as
select	f.id AS id,
		t.numeroDocumento AS numeroDocumento,
        t.nombre AS nombre,
        t.apellido AS apellido,
        concat(u.nombre, ' ', u.apellido) as responsable,
        f.contacto AS contacto,
        f.estado_id AS estado_id,
        e.descripcion as estado,
        ta.descripcion as tramiteAlta,
        f.observaciones,
        f.fechaPresentacion,
        f.numComitente
from 	formulario f 
join 	titular t 
on   	t.formulario_id = f.id
left outer join usuario u
on      f.responsable_id = u.id
left outer join estado e
on      f.estado_id = e.id
left outer join tramitealta ta
on      f.tramitealta_id = ta.id
where   f.retail_id is null