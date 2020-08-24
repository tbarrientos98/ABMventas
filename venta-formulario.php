<?php

include_once("config.php");
include_once("entidades/venta.php");
include_once("entidades/producto.php");
include_once("entidades/cliente.php");

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

//Select de clientes, recorridos por un foreach
$cliente = new Cliente();
$array_clientes = $cliente->obtenerTodos();

//Select de productos, recorridos por un foreach
$producto = new Producto();
$array_productos = $producto->obtenerTodos();

if ($_POST) {
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo una venta existente
            
            $venta->actualizar();
        } else {
            //Es nueva
            
            $venta->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        header("Location:venta-formulario.php");
        $venta->eliminar();
    }
}

if(isset($_GET["do"]) && $_GET["do"] == "buscarProducto" && $_GET["id"] && $_GET["id"] > 0){
    $idProducto = $_GET["id"];
    $producto = new Producto;
    $precioUnitario = $producto->obtenerPorPrecioUni($idProducto);
    echo json_encode($precioUnitario);
    exit;
}else if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $venta->obtenerPorId();
}

include_once("header.php");
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Formulario de ventas</title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body>

    <form action="" method="POST">

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <h1 class="h3 mb-4 text-gray-800">Ventas</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="ventas-listado.php" class="btn btn-info mr-2">Listado</a>
                    <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtFecha">Fecha:</label>
                    <input type="date" class="form-control" name="txtFecha" id="txtFecha" value="<?php echo date_format(date_create($venta->fecha), "Y-m-d"); ?>">
                </div>


                <div class="col-6 form-group">
                    <label for="txtHora">Hora:</label>
                    <input type="time" class="form-control" name="txtHora" id="txtHora" value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtCliente">Cliente:</label>

                    <select name="lstCliente" id="lstCliente" class="form-control">
                        <option value disabled selected>Seleccionar:</option>
                        <?php foreach ($array_clientes as $cliente) : ?>
                            <?php if ($venta->fk_idcliente == $cliente->idcliente) : ?>
                                <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="col-6 form-group">
                    <label for="lstProducto">Producto:</label>

                    <select name="lstProducto" id="lstProducto" class="form-control" onchange="fBuscarPrecio();">
                        <option value disabled selected>Seleccionar:</option>
                        <?php foreach ($array_productos as $producto) : ?>
                            <?php if ($venta->fk_idproducto == $producto->idproducto) : ?>
                                <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtPrecioUnitario">Precio unitario:</label>
                    <input type="text" class="form-control" name="txtPrecioUnitario" id="txtPrecioUnitario" value="<?php echo $venta->preciounitario; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtCantidad">Cantidad:</label>
                    <input type="text" class="form-control" name="txtCantidad" id="txtCantidad" onchange="fCalcularTotal();" value="<?php echo $venta->cantidad; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtTotal">Total:</label>
                    <input type="text" class="form-control" name="txtTotal" id="txtTotal" value="<?php echo $venta->total; ?>">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->

        <?php include_once("footer.php"); ?>
    </form>
    <script>
        function fBuscarPrecio() {
            var idProducto = $("#lstProducto option:selected").val();
            $.ajax({
                type: "GET",
                url: "venta-formulario.php?do=buscarProducto",
                data: {
                    id: idProducto
                },
                async: true,
                dataType: "json",
                success: function(respuesta) {
                    $("#txtPrecioUnitario").val(respuesta);
                }
            });

        }

        function fCalcularTotal() {
            var precio = $('#txtPrecioUnitario').val();
            var cantidad = $('#txtCantidad').val();
            var resultado = precio * cantidad;
            $("#txtTotal").val(resultado);

        }
    </script>
</body>