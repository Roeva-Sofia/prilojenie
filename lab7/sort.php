<?php

// проверка строки
function arg_is_not_Num($arg) {
    if ($arg === '') return true;
    for ($i = 0; $i < strlen($arg); $i++) {
        if ($arg[$i] < '0' || $arg[$i] > '9')
            return true;
    }
    return false;
}

// выб сорт
function selectionSort(&$arr, &$iterations) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $min = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            $iterations++; // сравнение
            if ($arr[$j] < $arr[$min]) {
                $min = $j;
            }
        }
        if ($min != $i) {
            $tmp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $tmp;
        }
        // вывод состояния после каждой итерации 
        echo "Итерация $iterations: " . implode(", ", $arr) . "<br>\n";
    }
}

// пузырьковая
function bubbleSort(&$arr, &$iterations) {
    $n = count($arr);
    for ($i = 0; $i < $n; $i++) {
        $swapped = false;
        for ($j = 0; $j < $n - 1; $j++) {
            $iterations++; // сравнение
            if ($arr[$j] > $arr[$j + 1]) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $tmp;
                $swapped = true;
            }
            echo "Итерация $iterations: " . implode(", ", $arr) . "<br>\n";
        }
        if (!$swapped) break;
    }
}

// шелл
function shellSort(&$arr, &$iterations) {
    $n = count($arr);
    // последовательность расстояний n/2, n/4, ..., 1
    for ($gap = floor($n / 2); $gap > 0; $gap = floor($gap / 2)) {
        for ($i = $gap; $i < $n; $i++) {
            $temp = $arr[$i];
            $j = $i;
            while ($j >= $gap && $arr[$j - $gap] > $temp) {
                $iterations++; // сравнение
                $arr[$j] = $arr[$j - $gap];
                $j -= $gap;
                echo "Итерация $iterations: " . implode(", ", $arr) . "<br>\n";
            }
            // последнее сравнение, которое не выполнило 
            if ($j >= $gap) $iterations++; 
            $arr[$j] = $temp;
        }
    }
}


// гномы
function gnomeSortFinal(&$arr, &$iterations) {
    $i = 1;
    $n = count($arr);
    while ($i < $n) {
        $iterations++;
        if ($i == 0 || $arr[$i] >= $arr[$i - 1]) {
            $i++;
        } else {
            $tmp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $tmp;
            $i--;
        }
        echo "Итерация $iterations: " . implode(", ", $arr) . "<br>\n";
    }
}

// быстрая сортировка
function quickSortRecursive(&$arr, $left, $right, &$iterations) {
    if ($left >= $right) return;
    
    $pivot = $arr[($left + $right) >> 1]; // опорный элемент в середине
    $l = $left;
    $r = $right;
    
    while ($l <= $r) {
        while ($arr[$l] < $pivot) {
            $iterations++;
            $l++;
        }
        while ($arr[$r] > $pivot) {
            $iterations++;
            $r--;
        }
        if ($l <= $r) {
            // обмен
            $tmp = $arr[$l];
            $arr[$l] = $arr[$r];
            $arr[$r] = $tmp;
            $l++;
            $r--;
            echo "Итерация $iterations: " . implode(", ", $arr) . "<br>\n";
        }
    }
    
    if ($left < $r) quickSortRecursive($arr, $left, $r, $iterations);
    if ($l < $right) quickSortRecursive($arr, $l, $right, $iterations);
}

// чтобы не передавать границы вручную
function quickSort(&$arr, &$iterations) {
    quickSortRecursive($arr, 0, count($arr) - 1, $iterations);
}

// встроенная без подсч итерац
function builtinSort(&$arr) {
    sort($arr);
}



//существует ли в массиве ключ 'element0' если нет – форма не отправлена
if (!isset($_POST['element0'])) {
    echo "Массив не задан, сортировка невозможна";
    exit();
}

// валидация элементов
$arr = [];
$arrLength = (int)$_POST['arrLength'];
for ($i = 0; $i < $arrLength; $i++) {
    $elem = $_POST['element' . $i];
    if (arg_is_not_Num($elem)) {
        echo "Ошибка: элемент '{$elem}' не является целым числом";
        exit();
    }
    $arr[] = (int)$elem;
}

// вывод исходного массива
echo "<h2>Исходный массив:</h2>";
echo implode(", ", $arr) . "<br><br>";
echo "Массив проверен, сортировка возможна<br><br>";

$time_start = microtime(true);
$iterations = 0;

// выбор алгоритма
$algo = $_POST['algoritm'];
switch ($algo) {
    case '0':
        echo "<h3>Сортировка выбором</h3>";
        selectionSort($arr, $iterations);
        break;
    case '1':
        echo "<h3>Пузырьковая сортировка</h3>";
        bubbleSort($arr, $iterations);
        break;
    case '2':
        echo "<h3>Сортировка Шелла</h3>";
        shellSort($arr, $iterations);
        break;
    case '3':
        echo "<h3>Гномья сортировка</h3>";
        gnomeSortFinal($arr, $iterations);
        break;
    case '4':
        echo "<h3>Быстрая сортировка</h3>";
        quickSort($arr, $iterations);
        break;
    case '5':
        echo "<h3>Встроенная функция sort()</h3>";
        builtinSort($arr);
        // для встроенной сортировки итерации не подсчитываем
        echo "Результат: " . implode(", ", $arr) . "<br>";
        break;
    default:
        echo "Неизвестный алгоритм";
        exit();
}

$time_spent = microtime(true) - $time_start;

echo "<br>Сортировка завершена, проведено $iterations итераций.<br>";
echo "Сортировка заняла " . number_format($time_spent, 6) . " секунд";

?>