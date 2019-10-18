<?php

function testFichero($archivo) {//Funcion que nos informa si hay algun error
    $codigosErrorSubida= [
        0 => 'Subida correcta',
        1 => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
        2 => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
        3 => 'El archivo no se pudo subir completamente',
        4 => 'No se seleccionó ningún archivo para ser subido',
        6 => 'No existe un directorio temporal donde subir el archivo',
        7 => 'No se pudo guardar el archivo en disco',  // permisos
        8 => 'Una extensión PHP evito la subida del archivo'  // extensión PHP
    ]; 
    $mensaje = ''; //devuelve un mensaje vacio si no hay ningun error
    if (! isset($_FILES[$archivo]['name'])) {
        $mensaje .= 'ERROR: No se indicó el archivo y/o no se indicó el directorio';
        
    } else {
        
        $directorioSubida = "/home/alummo2019-20/prueba";
        $nombreFichero   =   $_FILES[$archivo]['name'];
        $tipoFichero     =   $_FILES[$archivo]['type'];
        $tamanioFichero  =   $_FILES[$archivo]['size'];
        $temporalFichero =   $_FILES[$archivo]['tmp_name'];
        $errorFichero    =   $_FILES[$archivo]['error'];

        if ($errorFichero > 0) {
            $mensaje .= "Se a producido el error: $errorFichero:"
            . $codigosErrorSubida[$errorFichero] . ' <br />';
        } else { // subida correcta del temporal
            // si es un directorio y tengo permisos
            if($tipoFichero == "image/jpeg" || $tipoFichero  == "image/png"){ //comprobacion de tipo de archivo
                if(!(file_exists($directorioSubida . "/" . $nombreFichero))){ //comprobacion si ese fichero ya existe
                    if ( !(is_dir($directorioSubida)) && !(is_writable ($directorioSubida))) { // si el directorio es de lectura y escritura
                        //Intento mover el archivo temporal al directorio indicado
                        $mensaje .= 'ERROR: No es un directorio correcto o no se tiene permiso de escritura <br />';
                    }
                } else {
                    $mensaje.= 'El archivo ya existe en la ruta especificada <br>';
                    
                }
            } else {
                $mensaje .= 'El tipo de archivo no es el correcto';
            }
        }
    }
    return $mensaje;
}

function moverArchivo($archivo) {
    $mensaje="";
    $directorioSubida = "/home/alummo2019-20/prueba";
    $temporalFichero =   $_FILES[$archivo]['tmp_name'];
    if (move_uploaded_file($temporalFichero,  $directorioSubida .'/'. $_FILES[$archivo]['name']) == true) {
        $mensaje .= 'Archivo guardado en: ' . $directorioSubida .'/'. $_FILES[$archivo]['name'] . ' <br />';
    } else {
        $mensaje .= 'ERROR: Archivo no guardado correctamente <br />';
    }
    return $mensaje;
}

?>

<body>
    <?php echo " Bienvenido ".$_REQUEST['nombre']."<br>"?>
	<?php
	echo " ARCHIVO 1";
	echo "<br>";
	
	if(testFichero('archivo1') == ''){
	    //comrpobamos que el tamaño de los ficheros es el correcto
	    if($_FILES['archivo1']['size'] + $_FILES['archivo2']['size'] < 300000
	        && $_FILES['archivo1']['size'] < 200000 ){
	        
	       echo moverArchivo('archivo1');
	       echo "<br>";
	       
	    } else {
	        
	        echo "La dimension de los archivos no es la adecuada";
	        
	    }
	    
	} else {
	    
	    echo testFichero('archivo1');
	}
	
	echo "ARCHIVO 2";
	echo "<br>";
	
	if(testFichero('archivo2') == ''){
	    
	    if($_FILES['archivo1']['size'] + $_FILES['archivo2']['size'] < 300000
	          && $_FILES['archivo2']['size'] < 200000){
	        
	            echo moverArchivo('archivo2');
	            echo "<br>";
	            
	    } else {
	        
	        echo "La dimension de los archivos no es la adecuada";
	    }
	    
	} else {
	    
	    echo testFichero('archivo2');
	}
	
    ?>	
<br/>
<a href="Tarea02.php">Volver a la página de subida</a> <!-- para volver al formulario --> 
</body>
</html>