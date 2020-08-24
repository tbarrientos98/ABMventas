<?php

include_once("config.php");
include_once("entidades/cliente.php");


$cliente = new Cliente();
$array_clientes = $cliente->obtenerTodos();

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

    <title>Listado de clientes</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head> 

<body>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">Listado de clientes</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            </div>
        </div>
 
        <table class="table table-hover border table-light">
            <tr>
                <th>CUIT</th>
                <th>Nombre</th>
                <th>Fecha nac.</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($array_clientes as $cliente): ?>
                <tr>
                    <td><?php echo $cliente->cuit; ?></td>
                    <td><?php echo $cliente->nombre; ?></td>
                    <td><?php echo date_format(date_create($cliente->fecha_nac), 'd/m/Y'); ?></td>
                    <td><?php echo $cliente->telefono; ?></td>
                    <td><?php echo $cliente->correo; ?></td>
                    <td><a href="cliente-formulario.php?id=<?php echo $cliente->idcliente; ?>"><i class="fas fa-edit"></i></a></td>                    
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <!-- /.container-fluid -->
    
</body>
<?php include_once("footer.php"); ?>