<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблица умножения - Лабораторная работа А-5</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }
        /* меню */ 
        #main_menu {
            background-color: #333;
            padding: 15px;
            text-align: center;
        }
        
        #main_menu a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            color: #fff;
            text-decoration: none;
            background-color: #555;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        #main_menu a:hover {
            background-color: #777;
        }
        
        /* выделенный пункт */
        #main_menu a.selected {
            background-color: #4CAF50;
            font-weight: bold;
        }
        
        #main_menu a.selected:hover {
            background-color: #45a049;
        }
        
        #container {
            display: flex;
            min-height: calc(100vh - 120px);
        }
        
        #product_menu {
            width: 220px;
            background-color: #e0e0e0;
            padding: 20px;
        }
        
        #product_menu a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 5px;
            color: #333;
            text-decoration: none;
            background-color: #fff;
            border-radius: 3px;
            transition: all 0.3s;
        }
        
        #product_menu a:hover {
            background-color: #ddd;
        }
        
        /* выделенный пункт сбоку */
        #product_menu a.selected {
            background-color: #2196F3;
            color: #fff;
            font-weight: bold;
        }
        
        #product_menu a.selected:hover {
            background-color: #1976D2;
        }
        
        /* сама таблица */
        #content {
            flex: 1;
            padding: 30px;
        }
        
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        /*  стили таблицы  */
        table.multiplication-table {
            border-collapse: collapse;
            width: 100%;
        }
        
        table.multiplication-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background-color: #fff;
        }
        
        table.multiplication-table tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        
        /* стии блоков */
        .ttRow {
            display: inline-block;
            vertical-align: top;
            margin: 10px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 120px;
        }
        
        .ttSingleRow {
            display: inline-block;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.2em;
        }
        
        .ttRow div, .ttSingleRow div {
            padding: 5px 0;
        }
        
        /* ссылка на числа в таблице */
        .num-link {
            color: #2196F3;
            text-decoration: none;
            font-weight: bold;
        }
        
        .num-link:hover {
            color: #FF5722;
            text-decoration: underline;
        }
        
        #footer {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        
        #footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<?php
/*превращает число в ссылку*/
function outNumAsLink($x) {
    if ($x >= 2 && $x <= 9) {
        // Ссылка передаёт параметр content, сохраняя текущий тип вёрстки (html_type)
        return '<a href="?content=' . $x . '" class="num-link">' . $x . '</a>';
    } else {
        return $x;
    }
}

/*один столбец таблицы*/
function outRow($n) {
    for ($i = 2; $i <= 9; $i++) {
        // Используем outNumAsLink() для всех трёх чисел в строке
        echo outNumAsLink($n) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $n) . '<br>';
    }
}

/* выводит в виде таблицы*/
function outTableForm() {
    if (!isset($_GET['content'])) {
        // Выводим всю таблицу умножения (8 столбцов)
        echo '<table class="multiplication-table">';
        echo '<tr>';
        for ($i = 2; $i <= 9; $i++) {
            echo '<td>';
            outRow($i);      // выводим столбец для числа $i
            echo '</td>';
        }
        echo '</tr>';
        echo '</table>';
    } else {
        // Выводим один столбец (для выбранного числа)
        echo '<table class="multiplication-table">';
        echo '<tr><td>';
        outRow($_GET['content']);
        echo '</td></tr>';
        echo '</table>';
    }
}

/* выводит блоками*/
function outDivForm() {
    if (!isset($_GET['content'])) {
        // Выводим всю таблицу умножения: отдельный блок для каждого столбца
        for ($i = 2; $i <= 9; $i++) {
            echo '<div class="ttRow">';
            outRow($i);
            echo '</div>';
        }
    } else {
        // Выводим один столбец в крупном блоке
        echo '<div class="ttSingleRow">';
        outRow($_GET['content']);
        echo '</div>';
    }
}
?>

<!-- выбор типа вёрстки -->
<div id="main_menu">
    <?php
    //  ссылка "Табличная верстка"
    echo '<a href="?html_type=TABLE';
    if (isset($_GET['content'])) {
        echo '&content=' . $_GET['content'];
    }
    echo '"';
    if (isset($_GET['html_type']) && $_GET['html_type'] == 'TABLE') {
        echo ' class="selected"';
    }
    echo '>Табличная верстка</a>';
    
    //  ссылкf "Блочная верстка"
    echo '<a href="?html_type=DIV';
    // Сохраняем текущий content, если он был передан
    if (isset($_GET['content'])) {
        echo '&content=' . $_GET['content'];
    }
    echo '"';
    // Выделяем ссылку, если параметр html_type равен DIV
    if (isset($_GET['html_type']) && $_GET['html_type'] == 'DIV') {
        echo ' class="selected"';
    }
    echo '>Блочная верстка</a>';
    ?>
</div>


<div id="container">
    <!-- Боковое меню  -->
    <div id="product_menu">
        <?php
        // Ссылка полная таблица
        echo '<a href="?';
        // сохранение текущей вёрстки, если передано
        if (isset($_GET['html_type'])) {
            echo 'html_type=' . $_GET['html_type'];
        }
        echo '"';
        if (!isset($_GET['content'])) {
            echo ' class="selected"';
        }
        echo '>Всё</a>';
        
        //  ссылки для чисел 
        for ($i = 2; $i <= 9; $i++) {
            echo '<a href="?content=' . $i;
            if (isset($_GET['html_type'])) {
                echo '&html_type=' . $_GET['html_type'];
            }
            echo '"';
            // Выделяем ссылку, если параметр равен текущему числу
            if (isset($_GET['content']) && $_GET['content'] == $i) {
                echo ' class="selected"';
            }
            echo '>' . $i . '</a>';
        }
        ?>
    </div>
    
    <!-- Основная область с таблицей умножения -->
    <div id="content">
        <h2>
            <?php
            if (!isset($_GET['content'])) {
                echo 'Таблица умножения (полностью)';
            } else {
                echo 'Таблица умножения на ' . $_GET['content'];
            }
            ?>
        </h2>
        
        <?php
        //  функция вывода в зависимости от типа вёрстки
        if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
            outTableForm();   // Табличная вёрстка
        } else {
            outDivForm();     // блочная вёрстка
        }
        ?>
    </div>
</div>

<div id="footer">
    <?php
    if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
        $s = 'Табличная верстка. ';
    } else {
        $s = 'Блочная верстка. ';
    }
        if (!isset($_GET['content'])) {

        $s .= 'Таблица умножения полностью. ';
    } else {
        $s .= 'Столбец таблицы умножения на ' . $_GET['content'] . '. ';
    }
    
    echo '<p>' . $s . date('d.m.Y H:i:s', strtotime('+3 hours')) . '</p>';
    ?>
</div>

</body>
</html>
