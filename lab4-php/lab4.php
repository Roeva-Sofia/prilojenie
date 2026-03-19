<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Роева София. Группа 241-352. Лр 4.</title>

<style>
body{
    font-family: Arial;
    margin:40px;
}

h2{
    margin-top:30px;
}

table{
    border-collapse: collapse;
    margin-bottom:20px;
}

td{
    border:2px solid black;
    padding:10px;
    text-align:center;
}
</style>

</head>
<body>

<?php

// количество колонок таблицы
$cols = 5;

// структура таблиц
$structures = array(
"A*B*C#D*E*F",
"1*2*3#4*5*6",
"яблоко*груша*слива#банан*киви*манго",
"красный*зеленый*голубой#черный*белый*оранжевый",
"мяу*мяу*мяу#гав*гав*гав",
"данил*колбасенко*!!!#228*000*000",
"пипетка*а*б#в*г*д",
"123*123*123#12333*12333*12333",
"чтото*прол*прол#прол*прол*прол",
"1*2*3*4#5*6"
);

// формирование строки таблицы
function getTR($data, $cols)
{
    // разбив строки по символам и на отдельные ячейки
    $arr = explode('*', $data);

    if(count($arr) == 0 || $data == "")
        return "";

    $ret = "<tr>";

    //если данные есть/нет
    for($i=0; $i<$cols; $i++)
    {
        if(isset($arr[$i]))
            $ret .= "<td>".$arr[$i]."</td>";
        else
            $ret .= "<td></td>";
    }

    $ret .= "</tr>";

    return $ret;
}

// вывод таблицы
function outTable($structure, $cols)
{
    // проверка колич колонок
    if($cols == 0)
    {
        echo "Неправильное число колонок";
        return;
    }

    // строки по символу #
    $strings = explode('#', $structure);

    // если строк нет
    if(count($strings) == 0)
    {
        echo "В таблице нет строк";
        return;
    }

    // переменная для хранения всех строк таблицы
    $datas = "";

    // перебираем все строки 
    for($i=0; $i<count($strings); $i++)
    {
        $row = getTR($strings[$i], $cols);

        if($row != "")
            $datas .= $row;
    }

    // если строки есть — выводим таблицу
    if($datas != "")
        echo "<table>".$datas."</table>";
    else
        echo "В таблице нет строк с ячейками";
}

// цикл для вывода всех таблиц из массива
for($i=0; $i<count($structures); $i++)
{
    echo "<h2>Таблица №".($i+1)."</h2>";

    outTable($structures[$i], $cols);
}

?>

</body>
</html>