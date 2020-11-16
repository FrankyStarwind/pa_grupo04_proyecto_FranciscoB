<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Buscar datos</title>
    </head>
    <body>
        <h1>Resultado: </h1>
        <?php
        //Variables para coger lo introducido en el formulario
        $busqueda = filter_input(INPUT_GET, 'busqueda');
        $texto = filter_input(INPUT_GET, 'texto');
        $opcion = filter_input(INPUT_GET,'opcion');
        $op2 = filter_input(INPUT_GET, 'op2');
        $textop = filter_input(INPUT_GET, 'texto');
        $patron = '/Solicitante:[\s]+[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i'; //Patron para buscar solicitante
        $patron2 = '/Destinatario:[\s]+[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';//Patron para buscar destinatario
        $solicitante = 'Solicitante: comercial@maquial.es';
        $destinatario = 'Destinatario: mesaservicio@proveedor.es';
        $numSol = 0;
        $numDes = 0;
        $i = 0; 
        $busquedaSol = '';
        $busquedaDes = '';
        //Imprimir texto
        echo "<strong>TEXTO INTRODUCIDO: </strong><br>";
        echo $texto;
        echo '<br><br>';
        //arrays de pronombres y adjetivos
        $pronombres = array(' yo ', ' tu ', ' nosotros ', ' él ', ' ella ', ' se ');
        $adjetivos = array(' fea ', ' limpio ', ' rojo ', ' amarillo ', ' roto ', ' alegre ');
        
        //AÑADIMOS LA FUNCION PARA IDENTIFICAR AL SOLICITANTE (EPD 5)
        function identificarSolicitante($textop, $patron, $busquedaSol, $solicitante, $numSol){
            if(preg_match ($patron, $textop, $busquedaSol)){
                if($busquedaSol[0] == $solicitante){
                    $numSol = 1;
                }
                if($busquedaSol[0] != $solicitante){
                    echo "<strong>FALTA CORREO</strong><br><br>";
                    echo 'Falta por incluir: <br>';
                    echo $solicitante;
                    echo '<br>';
                }
            }
        }
        //AÑADIMOS LA FUNCION PARA IDENTIFICAR AL DESTINATARIO (EPD 5)
        function identificarDestinatario($textop, $patron2, $busquedaDes, $destinatario, $numDes){
            if(preg_match($patron2, $textop, $busquedaDes)){
                if($busquedaDes[0] == $destinatario){
                    $numDes = 1;
                }
                if($busquedaDes[0] != $destinatario){
                    echo "<strong>FALTA CORREO</strong><br><br>";
                    echo 'Falta por incluir: <br>';
                    echo $destinatario;
                    echo '<br>';
                }
            }
        }
        //Si en el desplegable seleccionamos Mesa de Servicios
        if($op2 == 'mesadeservicios'){
            identificarSolicitante($textop, $patron, $busquedaSol, $solicitante, $numSol);
            identificarDestinatario($textop, $patron2, $busquedaDes, $destinatario, $numDes);
            if($numSol == 1 && $numDes == 1){
                echo 'CORRECTO: <br>';
                echo $textop;
            }
        }
        
        function identificarPronombres($textop, $pronombres){
            $numP=0;
            echo "<strong>BUSQUEDA PRONOMBRES: </strong><br><br>";
            for($i=0; $i<=5; $i++){
                if(strpos($textop, $pronombres[$i]) !== false){
                    echo 'Se ha encontrado el pronombre [';
                    echo $pronombres[$i];
                    echo ']<br>';
                    echo str_ireplace($pronombres[$i],"<span style='background-color: #4BC835'>$pronombres[$i](pronombre)</span>",$textop);
                    $numP = $numP + substr_count($textop, $pronombres[$i]);
                    echo '<br><br>';
                }
            }
            echo "Se han encontrado $numP pronombres.<br><br>";
        }
        
        function identificarAdjetivos($textop, $adjetivos){
            $numA=0;
            echo "<strong>BUSQUEDA ADJETIVOS: </strong><br><br>";
            for($i=0; $i<=5; $i++){
                if(strpos($textop, $adjetivos[$i]) !== false){
                    echo 'Se ha encontrado el adjetivo [';
                    echo $adjetivos[$i];
                    echo ']<br>';
                    echo str_ireplace($adjetivos[$i],"<span style='background-color: #9DBADB'>$adjetivos[$i](adjetivo)</span>",$textop);
                    $numA = $numA + substr_count($textop, $adjetivos[$i]);
                    echo '<br><br>';
                }             
            }
            echo "Se han encontrado $numA adjetivos.<br><br>";
        }
        
        function resaltarPalabras($texto, $busqueda){
            echo "<strong>PALABRAS RESALTADAS: </strong><br><br>";
            echo str_ireplace($busqueda,"<span style='background-color: #ffff33'>$busqueda</span>",$texto);
            echo "<br /><br />";
            echo "La palabra $busqueda se repite: " . substr_count($texto,$busqueda) . " veces<br><br>";   
        }
        
        if($busqueda != null && strpos($texto, $busqueda) !== false){
            resaltarPalabras($texto, $busqueda);
        }
        if($busqueda != null){
            identificarPronombres($texto, $pronombres);
            identificarAdjetivos($textop, $adjetivos);
        }
         
        if($busqueda != null && strpos($texto, $busqueda) === false){
            echo 'MENSAJE: NO SE HA ENCONTRADO LA PALABRA.';
        }
        if($busqueda == null){
            echo 'MENSAJE: NO SE HA INTRODUCIDO NINGUNA PALABRA.<br><br>';
        }
        if($opcion=='frases'){
            echo "<strong>NUMERO DE FRASES: </strong><br>";
            $numfrases = count(explode('.', $texto));
            $num = $numfrases - 1;
            echo "La cadena contiene $num frases.<br><br>";
        }
        if($opcion=='palabras'){
            echo "<strong>NUMERO DE PALABRAS: </strong><br>";
            echo str_word_count($texto);
            echo '<br>';
        }
        if($opcion=='caracteres'){
            echo "<strong>NUMERO DE CARACTERES: </strong><br>";
            $numcaracteres = strlen($texto);
            echo "El número de caracteres que tiene la cadena es: $numcaracteres.<br><br>";
        }
        //TABLA DE BUSUEDA DE PRONOMBRES Y ADJETIVOS
        echo "<strong>TABLA PRONOMBRES: </strong><br>"; 
        echo "<table border='1'>";
        echo "<tr>";
        echo "  <th> Pronombres  </th>";
        echo "  <th> Numero </th>";
        echo "</tr>";
        for($i=0; $i<=5; $i++){
            echo "<tr>  <td>" . $pronombres[$i] . "</td> <td>" . substr_count($texto,$pronombres[$i]) ."</td>  </tr>" ;
        }
        echo "</table>";
        
        echo "<strong>TABLA ADJETIVOS: </strong><br>"; 
        echo "<table border='1'>";
        echo "<tr>";
        echo "  <th> Adjetivos  </th>";
        echo "  <th> Numero </th>";
        echo "</tr>";
        for($i=0; $i<=5; $i++){
            echo "<tr>  <td>" . $adjetivos[$i] . "</td> <td>" . substr_count($texto,$adjetivos[$i]) ."</td>  </tr>" ;
        }
        echo "</table>";
        
        ?>
    </body>
</html>
