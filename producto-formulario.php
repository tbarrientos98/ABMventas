<?php

include_once("config.php");
include_once("entidades/tipo_producto.php");
include_once("entidades/producto.php");

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

$tipoProducto = new TipoProducto();
$array_tipo_producto = $tipoProducto->obtenerTodos();

if ($_POST) {

    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {

            //Actualizo un producto existente
            if ($_FILES["txtImagen"]["error"] === UPLOAD_ERR_OK) {
                $nombreImagen = "";
                $nombreAleatorio = date("Ymdhmsi");
                $archivo_tmp = $_FILES["txtImagen"]["tmp_name"];
                $nombreArchivo = $_FILES["txtImagen"]["name"];
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nombreImagen = $nombreAleatorio . "." . $extension;
                move_uploaded_file($archivo_tmp, "archivos/$nombreImagen");
                $producto->imagen = $nombreImagen;
            } else{
                
                $productoAux = new Producto();
                $productoAux->idproducto = $_GET["id"];
                $productoAux->obtenerPorId();
                $producto->imagen =  $productoAux->imagen;
            }
            if ($imagenAnterior != ""){
                unlink("archivos/$imagenAnterior");
            }        
            $producto->actualizar();
           
        } else {
            //Es nuevo
            if ($_FILES["txtImagen"]["error"] === UPLOAD_ERR_OK) {
                $nombreImagen = "";
                $nombreAleatorio = date("Ymdhmsi");
                $archivo_tmp = $_FILES["txtImagen"]["tmp_name"];
                $nombreArchivo = $_FILES["txtImagen"]["name"];
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nombreImagen = $nombreAleatorio . "." . $extension;
                move_uploaded_file($archivo_tmp, "archivos/$nombreImagen");
            }
            $producto->imagen = $nombreImagen;
            $producto->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        header("Location:producto-formulario.php");
        $producto->eliminar();
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $producto->obtenerPorId();
}

include_once("header.php");
?>

<body>

    <form action="" method="POST"  enctype="multipart/form-data">

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <h1 class="h3 mb-4 text-gray-800">Productos</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="productos-listado.php" class="btn btn-info mr-2">Listado</a>
                    <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtImagen">Imagen:</label>
                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" value="<?php echo $producto->imagen; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-3 form-group">
                    <label for="txtCantidad">Cantidad:</label>
                    <input type="number" class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad; ?>">
                </div>
                <div class="col-3 form-group">
                    <label for="txtPrecio">Precio:</label>
                    <input type="text" class="form-control" name="txtPrecio" id="txtPrecio" value="<?php echo $producto->precio; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtNombre">Tipo de producto:</label>

                    <select name="lstTipoProducto" id="lstTipoProducto" class="form-control">
                        <option value disabled selected>Seleccionar:</option>
                        <?php foreach ($array_tipo_producto as $tipo) : ?>
                            <?php if ($producto->fk_idtipoproducto == $tipo->idtipoproducto) : ?>
                                <option selected value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    <label for="txtCorreo">Descripción:</label>
                    <textarea type="text" name="txtDescripcion" id="txtDescripcion"><?php echo $producto->descripcion; ?></textarea>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

        <?php include_once("footer.php"); ?>

        <script>
            ClassicEditor
                .create(document.querySelector('#txtDescripcion'))
                .catch(error => {
                    console.error(error);
                });
        </script>
        <style>
            .ck.ck-editor {
                height: 600px;
            }
        </style>
    </form>
</body>