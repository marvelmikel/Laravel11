<?php
// funcion recursiva del buscaminas
function checkForMines($row, $col, $map, &$auxMap)
{
    if ($row < 0 || $row >= $_SESSION["size"]) {
        return;
    }

    if ($col < 0 || $col >= $_SESSION["size"]) {
        return;
    }

    if ($_SESSION["showMap"][$row][$col] == 'B') {
        return;
    }

    if ($auxMap[$row][$col]) {
        return;
    }


    if ($map[$row][$col] == '*') {
        $auxMap[$row][$col] = '*';
        return;
    }

    $auxMap[$row][$col] = true;
    checkForMines($row - 1, $col, $map, $auxMap);
    checkForMines($row + 1, $col, $map, $auxMap);
    checkForMines($row, $col - 1, $map, $auxMap);
    checkForMines($row, $col + 1, $map, $auxMap);
}

// iniciando variables 
session_start();
$freeBoxes = $_SESSION["freeBoxes"];
$size = $_SESSION["size"];
$minesNumber = $_SESSION["minesNumber"];
$map = $_SESSION["matrixMap"];

for ($i = 0; $i < $size; $i++) {
    for ($j = 0; $j < $size; $j++) {
        $auxMap[$i][$j] = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game</title>
    <link rel="icon" href="./assets/img/logo.png" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

    <style media="screen">
        .box {
            width: 50px;
            height: 50px;
            background-color: gray;
            margin: 0 auto;
            color: black;
            cursor: pointer;
            font-family: 'Consolas';
            font-weight: bold;
        }

        body {
            background-color: darkgrey;
        }

        .center {
            margin: auto;
            width: 70%;
            border: 10px;
            text-align: center;
            padding: 10px;
            background-color: darkgrey;
        }

        h2,
        h3,
        h4,
        h5 {
            font-family: 'Press Start 2P', cursive;
        }
    </style>


</head>

<body oncontextmenu="return false;">
    <?php
    if (!isset($_SESSION['timer'])) {
        $_SESSION['timer'] = time();
    }

    if (isset($_GET["row"]) && isset($_GET["col"]) && !isset($_GET["change"])) {
        $parameterI = (int)$_GET["row"];
        $parameterJ = (int)$_GET["col"];

        // finalizar juego
        if ($map[$parameterI][$parameterJ] == '*') {
            header("location: ./index.php?gameOver=1");
        } else {
            checkForMines($parameterI, $parameterJ, $map, $auxMap);

            for ($i = 0; $i < $size; $i++) {
                for ($j = 0; $j < $size; $j++) {
                    if ($auxMap[$i][$j]) {
                        $_SESSION["showMap"][$i][$j] = $map[$i][$j];
                    }
                }
            }

            for ($i = 0; $i < $size; $i++) {
                for ($j = 0; $j < $size; $j++) {
                    if ($_SESSION["showMap"][$i][$j] != 'x' && $_SESSION["showMap"][$i][$j] != '*') {
                        $freeBoxes --;
                    }
                }
            }

            if ($freeBoxes <= 0){
                $now = time();
                $_SESSION["gameTime"] = $now - $_SESSION['timer'];
                header("location: ./win_page.php");
            }
        }
    } elseif (isset($_GET["row"]) && isset($_GET["col"]) && isset($_GET["change"])) {
        $parameterI = (int)$_GET["row"];
        $parameterJ = (int)$_GET["col"];

        if ($map[$parameterI][$parameterJ] == '*') {
            $_SESSION["showMap"][$parameterI][$parameterJ] = 'B';
            $_SESSION["minesFound"]++;

            if ($_SESSION["minesFound"] == $minesNumber) {
                $now = time();
                $_SESSION["gameTime"] = $now - $_SESSION['timer'];
                header("location: ./win_page.php");
            }
        }
    }
    ?>

    <div class="center">
        <h2>Buscaminas</h2>
        <h4 style="color:orangered">Marca todas las minas para ganar</h4>
        <h5 style="color:orangered">* Presione click derecho sobre una casilla para descubrirla<br>* Presione click izquierdo sobre alguna casilla para marcar una mina</h5>
        <table>
            <?php
            for ($i = 0; $i < $size; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $size; $j++) {

                    if ($_SESSION["showMap"][$i][$j] != 'x' && $_SESSION["showMap"][$i][$j] != '*') {

                        if ($_SESSION["showMap"][$i][$j] != '0' && $_SESSION["showMap"][$i][$j] != 'B') {
                            echo "<td class='box'  style='color:black; background-color:#bffa84'>" . $map[$i][$j] . "</td>";
                        } elseif ($_SESSION["showMap"][$i][$j] == 'B') {
                            echo "<td class='box'  style='color:black; background-color:#e30e0e'>" . $map[$i][$j] . "</td>";
                        } else {
                            echo "<td class='box'  style='color:black; background-color:#bffa84'></td>";
                        }
                    } else {
                        echo "<td class='box'  style='color:black; background-color:lightgray'><a onmousedown='readPosition(" . $i . "," . $j . ", event);' >?</a></td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>

        <h3>
            <?php
            if ($_SESSION["minesFound"] > 0) {
                echo "Minas encontradas: " . $_SESSION["minesFound"] . "<br>";
            }
            ?>
        </h3>

        <br>

        <label>
            <h3 id="time"></h3>
        </label>

        <br>

        <button><a href="./close_session.php">Reiniciar</a></button>
    </div>

    <script type="text/javascript">
        function readPosition(row, col, event) {
            if (event.button == 2) {
                document.location = "game.php?row=" + row + "&col=" + col + "&change=1";

            } else if (event.button == 0) {
                document.location = "game.php?row=" + row + "&col=" + col;
            }
        }

        window.setInterval(function() {
            let date = new Date();

            if (date.getMinutes() < 10) {
                minutes = "0" + date.getMinutes();
            } else {
                minutes = "" + date.getMinutes();
            }

            if (date.getSeconds() < 10) {
                seconds = "0" + date.getSeconds();
            } else {
                seconds = "" + date.getSeconds();
            }
            document.getElementById("time").innerHTML = date.getHours() + ":" + minutes + ":" + seconds;
        }, 1000);
    </script>
</body>

</html>