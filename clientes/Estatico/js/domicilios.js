
$(document).ready(function (){

    var productos = [];
    var pedido = {}; //{usuario:cliente1, total:120, productos[{codigo:r_r3, cantidad:2, tipo:tipo}]}

    pedido.total = 0;
    pedido.productos = [];

    var parametro = ((window.location.href).split("="))[1];
    if(parametro == 'solicitar_domicilio'){

        $('#tbody_importante_domicilio').ready(function(){

            var children = $('#tbody_importante_domicilio').children();

            children.each(function(){
               var c = $(this).children();

                var i = 0;
                var temp = {};
                c.each(function(){

                    if(i == 0)
                       temp.nombre = $(this).html();
                    if(i == 1)
                        temp.cantidad = $(this).html();
                    if(i == 2)
                        temp.precio = $(this).html();

                    if(i == 3){

                        var id = $(this).attr('id');
                        temp.codigo = id;
                        var car = id.substring(0, 1);
                        temp.tipo = (car == 'p') ? 'producto':'receta';
                        productos.push(temp);
                    }

                    i++;
                });

            });

            //esta listo el array de objectos receta

        });

    }


    $('.items_domicilios').each(function() {
        $(this).click(function () {

            var codigo = $(this).attr('id');
            var producto = buscarProducto(codigo);
            var titulo = "Digite la cantidad de " + producto.nombre;

            swal(
                {
                    title: "Cantidad",
                    text: titulo,
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    animation: "slide-from-top",
                    inputPlaceholder: "cantidad"
                },
                function (inputValue) {
                    if (inputValue === false)
                        return false;
                    if (inputValue === "") {
                        swal.showInputError("No puedes dejar este campo");
                        return false;
                    }

                    var cantidad = parseInt(inputValue);
                    if($.isNumeric( cantidad ) && cantidad <= producto.cantidad){

                        agregarProducto(producto, cantidad);

                    }else{
                        if(producto.tipo == 'receta')
                            agregarProducto(producto, cantidad);
                        else
                            alert('Debe ser una cantidad valida.\n Verifica los campos e intente nuevamente..');
                    }

                }
            );
        });
    });

    function yaEsta(codigo){

        for(var i = 0; i < pedido.productos.length ; i++){
            if(pedido.productos[i].codigo === codigo)
                return i;
        }

        return -1;
    }

    function pintarPedido(){

        var cuerpo = '';
        for(var i = 0; i< pedido.productos.length; i++){
            var p = pedido.productos[i];
            cuerpo += '<input type="text" name="productos[]" value="' + p.cantidad + " - " + p.nombre + '" id="item'+i+'" disabled>';
        }

        $('.container-items').html(cuerpo);
        $('#total_cuenta').html(pedido.total);

    }

    $('#boton-domicilio').click(function(){

        if( !pedido.listo ){
            swal({
                title: "No ha seleccionado articulos.",
                text: "Por favor selecciona algunos e intenta nuevamente...",
                timer: 1500,
                showConfirmButton: false });
            return;
        }

        swal({

            title: "Digita la dirección de tu domicilio",
            text: "",
            type: "input",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Solicitar!",
            closeOnConfirm: true },
            function(direccion){

                if(direccion == ''){
                    alert('Digita su dirección por favor.');
                    return;

                }

                var data = {};
                data.pedido = pedido;
                pedido.usuario = usuario;
                pedido.direccion = direccion;
                data.tipo = 'pedir_domicilio';

                $.ajax({
                    data : data,
                    type : 'POST',
                    url : "index.php",
                    success: function(result){
                        window.location.href = 'index.php?accion=domicilios';
                    }
                });


             }
        );

    });

    function agregarProducto(producto, cantidad) {

        var precio = parseInt( producto.precio.replace('.','') );
        pedido.total = pedido.total + ( precio * cantidad );

        var pos = yaEsta(producto.codigo);

        if( pos == -1){

            var temp = {
                nombre : producto.nombre,
                cantidad : cantidad,
                precio : producto.precio,
                tipo : producto.tipo,
                codigo : producto.codigo
            };
            pedido.productos.push(temp);

        }else{
            pedido.productos[pos].cantidad += cantidad;
        }

        pintarPedido();
        pedido.listo = true;
    }


    function buscarProducto(codigo) {

        for(var i = 0; i < productos.length; i++){
            if(productos[i].codigo == codigo){
                return productos[i];
            }
        }

    }
});