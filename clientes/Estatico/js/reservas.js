
$(document).ready(function () {

    var reserva = {};
    
    reserva.cantidad = 0;
    reserva.usuario = '';
    reserva.listo = false;

    var mesas = [];

    $('#contenedor_importante').ready(function () {
        $('.radio_mesas').each(function () {

            var cantidad = $('#c_' + $(this).attr('id')).html();


            mesas.push({
                id : $(this).attr('id'),
                cantidad : cantidad
            });


            $(this).click(function () {
                if ($(this).attr('id') == 'otro' ) {

                    //var x = prompt('cantidad de personas', 'a');
                    //agregarMesa(x);

                }else {
                    agregarMesa($(this).attr('value'));
                }

            });
        });
    });


    function agregarMesa(id, val) {

        reserva.listo = true;
        reserva.mesa = id;
        pintarMesas();
    }

    function pintarMesas(){
        $('#usuario_usuario').val('Mesa No. : ' + reserva.mesa);
    }

    $('#boton-reserva').click(function(){

        if( !reserva.listo ){
            swal({
                title: "No ha seleccionado mesas.",
                text: "Por favor selecciona alguna intenta nuevamente...",
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        swal({

                title: "A nombre de quien quieres la reserva?",
                text: "",
                type: "input",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Reservar!",
                closeOnConfirm: true },
            function(nombre){

                if(nombre == ''){
                    alert('Digita un nombre por favor.');
                    return;
                }

                var data = {};
                data.reserva = reserva;
                reserva.usuario = usuario;
                reserva.fecha = new Date();
                reserva.nombre = nombre;
                data.tipo = 'pedir_reserva';

                $.ajax({
                    data : data,
                    type : 'POST',
                    url : "index.php",
                    success: function(result){
                        window.location.href = 'index.php?accion=reservas';
                    },
                    error: function(){
                        //window.location.href = 'index.php?accion=reservas';
                    }
                });


            }
        );

    });



});