<?php

include_once("config.php");
include_once("entidades/producto.php");


$producto = new Producto();
$array_productos = $producto->obtenerTodos();

include_once "header.php";
?>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <h1 class="h3 mb-4 text-gray-800">Listado de productos</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                </div>
            </div>

            <table class="table table-hover border table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($array_productos as $producto) :  ?>
                    
                    <tr>
                        <td><img src="archivos/<?php echo $producto->imagen; ?>" alt="" class="img-thumbnail" style="height: 50px;"></td>
                        <td><?php echo $producto->nombre; ?></td>
                        <td><?php echo $producto->cantidad; ?></td>
                        <td><?php echo "$" . number_format($producto->precio, 2, ",", "."); ?></td>
                        <td><a href="producto-formulario.php?id=<?php echo $producto->idproducto; ?>"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <!-- /.container-fluid -->
    </form>

</body>
<?php include_once("footer.php"); ?>