<?php

include_once("config.php");
include_once("entidades/cliente.php");
include_once("entidades/localidad.php");
include_once("entidades/provincia.php");
include_once("entidades/domicilio.php");

$cliente = new Cliente();
$cliente->cargarFormulario($_REQUEST);

$provincia = new Provincia();
$array_provincias = $provincia->obtenerTodos();

if ($_POST) {
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo un cliente existente
            $cliente->actualizar();
        } else {
            //Es nuevo
            $cliente->insertar();
        }
        if(isset($_POST["txtTipo"])){
            $domicilio = new Domicilio();
            $domicilio->eliminarPorCliente($cliente->idcliente);
            for($i=0; $i < count($_POST["txtTipo"]); $i++){
                $domicilio->fk_idcliente = $cliente->idcliente; 
                $domicilio->fk_tipo = $_POST["txtTipo"][$i];
                $domicilio->fk_idlocalidad =  $_POST["txtLocalidad"][$i];
                $domicilio->domicilio = $_POST["txtDomicilio"][$i];
                $domicilio->insertar();
            }
        }
        
    } else if (isset($_POST["btnBorrar"])) {
        $domicilio = new Domicilio();
        $domicilio->eliminarPorCliente($cliente->idcliente);
        $cliente->eliminar();   
    }
}

if (isset($_GET["do"]) && $_GET["do"] == "buscarLocalidad" && $_GET["id"] && $_GET["id"] > 0) {
    $idProvincia = $_GET["id"];
    $localidad = new Localidad();
    $aLocalidad = $localidad->obtenerPorProvincia($idProvincia);
    echo json_encode($aLocalidad);
    exit;
} else if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $cliente->obtenerPorId();
}


if(isset($_GET["do"]) && $_GET["do"] == "cargarGrilla"){
    $idCliente = $_GET['idCliente'];
    $request = $_REQUEST;

    $entidad = new Domicilio();
    $aDomicilio = $entidad->obtenerFiltrado($idCliente);

    $data = array();

    if (count($aDomicilio) > 0)
        $cont=0;
        for ($i=0; $i < count($aDomicilio); $i++) {
            $row = array();
            $row[] = $aDomicilio[$i]->tipo;
            $row[] = $aDomicilio[$i]->provincia;
            $row[] = $aDomicilio[$i]->localidad;
            $row[] = $aDomicilio[$i]->domicilio;
            $cont++;
            $data[] = $row;
        }

    $json_data = array(
        "draw" => isset($request['draw'])?intval($request['draw']):0,
        "recordsTotal" => count($aDomicilio), //cantidad total de registros sin paginar
        "recordsFiltered" => count($aDomicilio),//cantidad total de registros en la paginacion
        "data" => $data
    );
    echo json_encode($json_data);
    exit;
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

    <title>Formulario de clientes</title>

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

            <h1 class="h3 mb-4 text-gray-800">Clientes</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="clientes-listado.php" class="btn btn-info mr-2">Listado</a>
                    <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $cliente->nombre; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtCuit">CUIT:</label>
                    <input type="text" class="form-control" name="txtCuit" id="txtCuit" value="<?php echo $cliente->cuit; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtFechaNac">Fecha de nacimiento:</label>
                    <input type="date" class="form-control" name="txtFechaNac" id="txtFechaNac" value="<?php echo $cliente->fecha_nac; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtTelefono">Teléfono</label>
                    <input type="text" class="form-control" name="txtTelefono" id="txtTelefono" value="<?php echo $cliente->telefono; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtCorreo">Correo:</label>
                    <input type="email" class="form-control" name="txtCorreo" id="txtCorreo" value="<?php echo $cliente->correo; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fa fa-table"></i> Domicilios
                            <div class="pull-right">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalDomicilio">Agregar</button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table id="grilla" class="display" style="width:98%">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Provincia</th>
                                        <th>Localidad</th>
                                        <th>Dirección</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modalDomicilio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Domicilio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12 form-group">
                                <label for="txtTipo">Tipo:</label>
                                <select class="form-control" name="lstTipo" id="lstTipo">
                                    <option disabled selected>Seleccionar</option>
                                    <option value="1">Personal</option>
                                    <option value="2">Laboral</option>
                                    <option value="3">Comercial</option>
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <label for="txtProvincia">Provincia:</label>

                                <select class="form-control" name="lstProvincia" onchange="fBuscarLocalidad();" id="lstProvincia">
                                    <option disabled selected>Seleccionar</option>
                                    <?php foreach ($array_provincias as $provincia) : ?>
                                        <?php if ($localidad->fk_idprovincia == $provincia->idprovincia) : ?>
                                            <option selected value="<?php echo $provincia->idprovincia; ?>"><?php echo $provincia->nombre; ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $provincia->idprovincia; ?>"><?php echo $provincia->nombre; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="col-12 form-group">
                                <label for="txtLocalidad">Localidad:</label>
                                <select class="form-control" name="lstLocalidad" id="lstLocalidad">
                                    <option value="" disabled selected>Seleccionar</option>
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <label for="txtDireccion">Dirección:</label>
                                <input type="text" class="form-control" name="txtDireccion" id="txtDireccion" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="fAgregarDomicilio();">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </form>
    <?php include_once("footer.php"); ?>

    <script>
        window.onload = function() {
            var idCliente = '<?php echo isset($cliente) && $cliente->idcliente > 0? $cliente->idcliente : 0 ?>';
            var dataTable = $('#grilla').DataTable({
                "processing": true,
                "serverSide": false,
                "bFilter": false,
                "bInfo": true,
                "bSearchable": false,
                "paging": false,
                "pageLength": 25,
                "order": [
                    [0, "asc"]
                ],
                "ajax": "cliente-formulario.php?do=cargarGrilla&idCliente=" + idCliente
            });

        };
        function fBuscarLocalidad() {
            idProvincia = $("#lstProvincia option:selected").val();
            $.ajax({
                type: "GET",
                url: "cliente-formulario.php?do=buscarLocalidad",
                data: {
                    id: idProvincia
                },
                async: true,
                dataType: "json",
                success: function(respuesta) {
                    $("#lstLocalidad option").remove();
                    $("<option>", {
                        value: 0,
                        text: "Seleccionar",
                        disabled: true,
                        selected: true
                    }).appendTo("#lstLocalidad");

                    for (i = 0; i < respuesta.length; i++) {
                        $("<option>", {
                            value: respuesta[i]["idlocalidad"],
                            text: respuesta[i]["nombre"]
                        }).appendTo("#lstLocalidad");
                    }
                    $("#lstLocalidad").prop("selectedIndex", "0");
                }
            });
        }

        function fAgregarDomicilio() {
            var grilla = $('#grilla').DataTable();
            grilla.row.add([
                $("#lstTipo option:selected").text() + "<input type='hidden' name='txtTipo[]' value='" + $("#lstTipo option:selected").val() + "'>",
                $("#lstProvincia option:selected").text() + "<input type='hidden' name='txtProvincia[]' value='" + $("#lstProvincia option:selected").val() + "'>",
                $("#lstLocalidad option:selected").text() + "<input type='hidden' name='txtLocalidad[]' value='" + $("#lstLocalidad option:selected").val() + "'>",
                $("#txtDireccion").val() + "<input type='hidden' name='txtDomicilio[]' value='" + $("#txtDireccion").val() + "'>"
            ]).draw();
            $('#modalDomicilio').modal('toggle');
            limpiarFormulario();
        }

        function limpiarFormulario() {
            $("#lstTipo").val("");
            $("#lstProvincia").val("");
            $("#lstLocalidad").val("");
            $("#txtDireccion").val("");
        }
    </script>
</body>