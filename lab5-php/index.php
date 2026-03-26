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
        
        /* Главное меню (горизонтальное) */
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
        
        #main_menu a.selected {
            background-color: #4CAF50;
            font-weight: bold;
        }
        
        #main_menu a.selected:hover {
            background-color: #45a049;
        }
        
        /* Контейнер для контента */
        #container {
            display: flex;
            min-height: calc(100vh - 120px);
        }
        
        /* Боковое меню (вертикальное) */
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
        
        #product_menu a.selected {
            background-color: #2196F3;
            color: #fff;
            font-weight: bold;
        }
        
        #product_menu a.selected:hover {
            background-color: #1976D2;
        }
        
        /* Основная область с таблицей умножения */
        #content {
            flex: 1;
            padding: 30px;
        }
        
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        /* Табличная верстка */
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
        
        /* Блочная верстка */
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
        
        /* Ссылки на числа в таблице */
        .num-link {
            color: #2196F3;
            text-decoration: none;
            font-weight: bold;
        }
        
        .num-link:hover {
            color: #FF5722;
            text-decoration: underline;
        }
        
        /* Подвал */
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
// Функция выводит число как ссылку (если число от 2 до 9)
function outNumAsLink($x) {
    if ($x >= 2 && $x <= 9) {
        return '<a href="?content=' . $x . '" class="num-link">' . $x . '</a>';
    } else {
        return $x;
    }
}

// Функция выводит столбец таблицы умножения
function outRow($n) {
    for ($i = 2; $i <= 9; $i++) {
        echo outNumAsLink($n) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $n) . '<br>';
    }
}

// Функция выводит таблицу умножения в табличной форме
function outTableForm() {
    if (!isset($_GET['content'])) {
        // Выводим всю таблицу умножения (8 столбцов)
        echo '<table class="multiplication-table"><tr>';
        for ($i = 2; $i <= 9; $i++) {
            echo '<td>';
            outRow($i);
            echo '</td>';
        }
        echo '</tr></table>';
    } else {
        // Выводим один столбец
        echo '<table class="multiplication-table"><tr><td>';
        outRow($_GET['content']);
        echo '</td></tr></table>';
    }
}

// Функция выводит таблицу умножения в блочной форме
function outDivForm() {
    if (!isset($_GET['content'])) {
        // Выводим всю таблицу умножения
        for ($i = 2; $i <= 9; $i++) {
            echo '<div class="ttRow">';
            outRow($i);
            echo '</div>';
        }
    } else {
        // Выводим один столбец
        echo '<div class="ttSingleRow">';
        outRow($_GET['content']);
        echo '</div>';
    }
}
?>

<!-- Главное меню -->
<div id="main_menu">
    <?php
    // Формируем ссылку "Табличная верстка"
    echo '<a href="?html_type=TABLE';
    // Добавляем параметр content, если он был передан (сопряжение меню)
    if (isset($_GET['content'])) {
        echo '&content=' . $_GET['content'];
    }
    echo '"';
    // Выделяем ссылку, если параметр html_type равен TABLE
    if (isset($_GET['html_type']) && $_GET['html_type'] == 'TABLE') {
        echo ' class="selected"';
    }
    echo '>Табличная верстка</a>';
    
    // Формируем ссылку "Блочная верстка"
    echo '<a href="?html_type=DIV';
    // Добавляем параметр content, если он был передан (сопряжение меню)
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

<!-- Контейнер для бокового меню и контента -->
<div id="container">
    <!-- Боковое меню -->
    <div id="product_menu">
        <?php
        // Ссылка "Всё" (таблица умножения полностью)
        echo '<a href="?';
        // Добавляем параметр html_type, если он был передан (сопряжение меню)
        if (isset($_GET['html_type'])) {
            echo 'html_type=' . $_GET['html_type'];
        }
        echo '"';
        // Выделяем ссылку, если параметр content не передан
        if (!isset($_GET['content'])) {
            echo ' class="selected"';
        }
        echo '>Всё</a>';
        
        // Цикл для создания ссылок на таблицы умножения от 2 до 9
        for ($i = 2; $i <= 9; $i++) {
            echo '<a href="?content=' . $i;
            // Добавляем параметр html_type, если он был передан (сопряжение меню)
            if (isset($_GET['html_type'])) {
                echo '&html_type=' . $_GET['html_type'];
            }
            echo '"';
            // Выделяем ссылку, если параметр content равен текущему значению
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
            // Заголовок в зависимости от выбранного содержимого
            if (!isset($_GET['content'])) {
                echo 'Таблица умножения (полностью)';
            } else {
                echo 'Таблица умножения на ' . $_GET['content'];
            }
            ?>
        </h2>
        
        <?php
        // Выводим таблицу умножения в зависимости от типа верстки
        // По умолчанию (если параметр не выбран) используется табличная верстка
        if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
            outTableForm();
        } else {
            outDivForm();
        }
        ?>
    </div>
</div>

<!-- Подвал с информацией -->
<div id="footer">
    <?php
    // Формируем строку с информацией о типе верстки
    if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
        $s = 'Табличная верстка. ';
    } else {
        $s = 'Блочная верстка. ';
    }
    
    // Добавляем информацию о содержании таблицы
    if (!isset($_GET['content'])) {
        $s .= 'Таблица умножения полностью. ';
    } else {
        $s .= 'Столбец таблицы умножения на ' . $_GET['content'] . '. ';
    }
    
    // Выводим информацию и текущую дату/время
    echo '<p>' . $s . date('d.m.Y H:i:s', strtotime('+3 hours')) . '</p>';
    ?>
</div>

</body>
</html>
