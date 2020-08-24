<?php

include_once("config.php");
include_once("entidades/venta.php");
include_once("entidades/producto.php");


$venta = new Venta();
$array_venta = $venta->cargarGrilla();

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

    <title>Listado de ventas</title>

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
 
        <h1 class="h3 mb-4 text-gray-800">Listado de ventas</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            </div>
        </div>

        <table class="table table-hover border table-light">
            <tr>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($array_venta as $venta): ?>
                <tr>
                    <td><?php echo date_format(date_create($venta->fecha),"d/m/Y H:i"); ?></td>
                    <td><?php echo $venta->cantidad; ?></td>
                    <td><?php echo $venta->nombre_producto; ?></td>
                    <td><?php echo $venta->nombre_cliente; ?></td>
                    <td>$<?php echo number_format($venta->total, 2, ",",".");?></td>
                    <td><a href="venta-formulario.php?id=<?php echo $venta->idventa; ?>"><i class="fas fa-edit"></i></a></td>                    
                </tr>
            <?php endforeach; ?>
        </table> 
    </div>
    <!-- /.container-fluid -->
    
</body>
<?php include_once("footer.php"); ?>