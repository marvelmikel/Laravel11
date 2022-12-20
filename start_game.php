<?php


    if (!isset($_GET["size"]) || !isset($_GET["username"])){
        header("location: ./index.php");
    }

    $size = $_GET["size"];
    $minesNumber = round(($size * $size) * 0.35);
    
    session_start();

    //creando la matriz
    for ($i = 0; $i < $size; $i++){
        for ($j = 0; $j < $size; $j++){
            $_SESSION["matrixMap"][$i][$j] = '0'; 
            $_SESSION["showMap"][$i][$j] = 'x';       
        }
    }
    
    // colocando minas aleatorias
    $minesLaid = 0;
    while ($minesLaid < $minesNumber){
        $row = rand(0,$size-1);
        $col = rand(0,$size-1);
        if ($_SESSION["matrixMap"][$row][$col] == '0' ){
            $_SESSION["matrixMap"][$row][$col] = '*';
            $minesLaid ++;
        }
    }

    //poniendo el numero de minas que hay alrededor de determinada casilla
    for ($i = 0; $i < $size; $i++){
        for ($j = 0; $j < $size; $j++){
            if ($_SESSION["matrixMap"][$i][$j] == '0'){
                // echo ("at ".$i." ".$j."<br>");
                $minesAtPosition = countMines($_SESSION["matrixMap"], $size, $i, $j);
                $_SESSION["matrixMap"][$i][$j] = strval($minesAtPosition);
            }        
        }
    }
    

    function countMines($matrix,$size, $row, $col){
        $minesAtPosition = 0;
        // diagonals
        if (($row - 1 >= 0 && $col - 1 >= 0) && $matrix[$row - 1][$col - 1] == '*'){
            $minesAtPosition ++;
        } 

        if (($row + 1 < $size && $col - 1 >= 0) && $matrix[$row + 1][$col - 1] == '*'){
            $minesAtPosition ++;
        }

        if (($row - 1 >= 0 && $col + 1 < $size) && $matrix[$row - 1][$col + 1] == '*'){
            $minesAtPosition ++;
        }

        if (($row + 1 < $size && $col + 1 < $size) && $matrix[$row + 1][$col + 1] == '*'){
            $minesAtPosition ++;
        }

        // up down and sides 
        if (($row - 1 >= 0) && $matrix[$row - 1][$col] == '*'){
            $minesAtPosition ++;
        }

        if (($row + 1 < $size) && $matrix[$row + 1][$col] == '*'){
            $minesAtPosition ++;
        }

        if (($col - 1 >= 0) && $matrix[$row][$col - 1] == '*'){
            $minesAtPosition ++;
        }

        if (($col + 1 < $size) && $matrix[$row][$col + 1] == '*'){
            $minesAtPosition ++;
        }
        
        return $minesAtPosition;
    }

    // imprimiendo la matriz
    for ($i = 0; $i < $size; $i++){
        for ($j = 0; $j < $size; $j++){
            echo $_SESSION["matrixMap"][$i][$j]." ";        
        }
        echo "<br>";
    }

    $_SESSION["size"] = $size;
    $_SESSION["minesNumber"] = $minesNumber;
    $_SESSION["minesFound"] = 0;
    $_SESSION["username"] = $_GET["username"];
    $_SESSION["freeBoxes"] = round($size * $size - $minesNumber);
    header("location: ./game.php");
?>