<?php
/**
 * Лабораторная работа №6
 * Студент: Роева София Михайловна
 * Группа: 241-352
 *
 * Скрипт реализует форму для тестирования математических задач.
 * Пользователь вводит ФИО, группу, числа A, B, C, выбирает задачу,
 * вводит свой ответ и получает результат с возможностью отправки отчёта по email.
 */

// Функция для получения случайного числа с плавающей точкой от 0 до 100
function randomFloat() {
    return mt_rand(0, 10000) / 100;
}

// Функция для преобразования введённой строки в число (запятая → точка)
function parseNumber($str) {
    $str = trim(str_replace(',', '.', $str));
    return is_numeric($str) ? (float)$str : null;
}

// Функция для вычисления результата в зависимости от выбранной задачи
function calculateResult($task, $a, $b, $c) {
    switch ($task) {
        case 'average':
            return ($a + $b + $c) / 3;
        case 'sum':
            return $a + $b + $c;
        case 'product':
            return $a * $b * $c;
        case 'max':
            return max($a, $b, $c);
        case 'min':
            return min($a, $b, $c);
        case 'volume':
            return $a * $b * $c;
        default:
            return null;
    }
}

// Функция для получения названия задачи по ключу
function getTaskName($task) {
    $names = [
        'average' => 'Среднее арифметическое',
        'sum'     => 'Сумма чисел',
        'product' => 'Произведение чисел',
        'max'     => 'Максимальное из трёх',
        'min'     => 'Минимальное из трёх',
        'volume'  => 'Объём параллелепипеда'
    ];
    return $names[$task] ?? 'Неизвестная задача';
}

$resultMessage = '';       // для вывода отчёта
$sendMailMessage = '';     // сообщение об отправке письма
$showRepeatLink = false;   // показывать ли ссылку "Повторить тест"

// ---- Обработка отправленной формы (POST) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['A'])) {
    // Получаем данные из формы
    $fio   = trim($_POST['FIO'] ?? '');
    $group = trim($_POST['GROUP'] ?? '');
    $about = trim($_POST['ABOUT'] ?? '');
    $task  = $_POST['TASK'] ?? '';
    $a_str = $_POST['A'] ?? '';
    $b_str = $_POST['B'] ?? '';
    $c_str = $_POST['C'] ?? '';
    $userAnswerStr = trim($_POST['USER_ANSWER'] ?? '');
    $sendMail = isset($_POST['SEND_MAIL']);
    $email = trim($_POST['EMAIL'] ?? '');
    $version = $_POST['VERSION'] ?? 'browser'; // browser / print

    // Преобразуем числа
    $a = parseNumber($a_str);
    $b = parseNumber($b_str);
    $c = parseNumber($c_str);
    $userAnswer = ($userAnswerStr === '') ? null : parseNumber($userAnswerStr);

    $errors = [];
    if ($a === null) $errors[] = 'Некорректное значение A';
    if ($b === null) $errors[] = 'Некорректное значение B';
    if ($c === null) $errors[] = 'Некорректное значение C';
    if (empty($fio)) $errors[] = 'Не заполнено ФИО';
    if (empty($group)) $errors[] = 'Не заполнена группа';
    if (empty($task)) $errors[] = 'Не выбрана задача';

    if (empty($errors)) {
        $correct = calculateResult($task, $a, $b, $c);
        if ($correct !== null) {
            $correct = round($correct, 3); // округляем до 3 знаков

            // Сравнение ответов (числа с плавающей точкой сравниваем с точностью 0.001)
            $isCorrect = ($userAnswer !== null && abs($userAnswer - $correct) < 0.001);

            // Формируем текстовый отчёт (HTML)
            $out_text = "<div class='report'>";
            $out_text .= "<h3>Результат тестирования</h3>";
            $out_text .= "<p><strong>ФИО:</strong> " . htmlspecialchars($fio) . "</p>";
            $out_text .= "<p><strong>Группа:</strong> " . htmlspecialchars($group) . "</p>";
            if (!empty($about)) {
                $out_text .= "<p><strong>О себе:</strong> " . nl2br(htmlspecialchars($about)) . "</p>";
            }
            $out_text .= "<p><strong>Тип задачи:</strong> " . getTaskName($task) . "</p>";
            $out_text .= "<p><strong>Входные данные:</strong> A = $a, B = $b, C = $c</p>";
            if ($userAnswer === null) {
                $out_text .= "<p><strong>Ваш ответ:</strong> <span class='error'>Задача самостоятельно решена не была</span></p>";
            } else {
                $out_text .= "<p><strong>Ваш ответ:</strong> " . htmlspecialchars($userAnswer) . "</p>";
            }
            $out_text .= "<p><strong>Правильный ответ:</strong> " . $correct . "</p>";
            if ($isCorrect) {
                $out_text .= "<p class='success'><strong>Тест пройден!</strong></p>";
            } else {
                $out_text .= "<p class='error'><strong>Ошибка: тест не пройден!</strong></p>";
            }
            $out_text .= "</div>";

            $resultMessage = $out_text;

            // Отправка email, если установлен флажок и email не пуст
            if ($sendMail && !empty($email)) {
                // Для письма удаляем HTML-теги и заменяем <br> на переносы строк
                $plainText = strip_tags(str_replace(['<br>', '</p>', '<p>'], ["\r\n", "\r\n", ''], $out_text));
                $plainText = str_replace(['<div class="report">', '</div>', '<h3>', '</h3>', '<strong>', '</strong>'], '', $plainText);
                $plainText = preg_replace('/<[^>]*>/', '', $plainText); // убираем все оставшиеся теги
                $plainText = trim($plainText);

                $subject = 'Результат тестирования';
                $headers = "From: auto@test.ru\r\n";
                $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

                if (mail($email, $subject, $plainText, $headers)) {
                    $sendMailMessage = "<p class='success'>Результаты теста были автоматически отправлены на e-mail " . htmlspecialchars($email) . "</p>";
                } else {
                    $sendMailMessage = "<p class='error'>Ошибка при отправке письма. Проверьте настройки почты.</p>";
                }
            } elseif ($sendMail && empty($email)) {
                $sendMailMessage = "<p class='error'>E-mail не указан, отправка невозможна.</p>";
            }

            // Показываем ссылку "Повторить тест" только для версии "браузер"
            if ($version === 'browser') {
                $showRepeatLink = true;
                // Сохраняем ФИО и группу в GET-параметры для ссылки
                $repeatUrl = "?FIO=" . urlencode($fio) . "&GROUP=" . urlencode($group);
            } else {
                $showRepeatLink = false;
            }
        } else {
            $resultMessage = "<p class='error'>Ошибка: задача не распознана.</p>";
        }
    } else {
        $resultMessage = "<div class='error'><ul><li>" . implode("</li><li>", $errors) . "</li></ul></div>";
    }
}

// ---- Определение значений по умолчанию для формы ----
// Если есть GET-параметры (пришли по ссылке "Повторить тест"), используем их для ФИО и группы
$defaultFio = isset($_GET['FIO']) ? htmlspecialchars($_GET['FIO']) : '';
$defaultGroup = isset($_GET['GROUP']) ? htmlspecialchars($_GET['GROUP']) : '';

// Для полей A, B, C всегда генерируем новые случайные числа (кроме случая, когда только что отправлена форма и есть POST)
// Но после POST мы всё равно показываем форму с теми же значениями, чтобы пользователь мог их видеть.
// Для этого при POST берём значения из $_POST, иначе случайные.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['A'])) {
    $defaultA = isset($_POST['A']) ? htmlspecialchars($_POST['A']) : randomFloat();
    $defaultB = isset($_POST['B']) ? htmlspecialchars($_POST['B']) : randomFloat();
    $defaultC = isset($_POST['C']) ? htmlspecialchars($_POST['C']) : randomFloat();
    $defaultAbout = isset($_POST['ABOUT']) ? htmlspecialchars($_POST['ABOUT']) : '';
    $defaultTask = isset($_POST['TASK']) ? $_POST['TASK'] : 'average';
    $defaultVersion = isset($_POST['VERSION']) ? $_POST['VERSION'] : 'browser';
    $defaultSendMail = isset($_POST['SEND_MAIL']);
    $defaultEmail = isset($_POST['EMAIL']) ? htmlspecialchars($_POST['EMAIL']) : '';
} else {
    $defaultA = randomFloat();
    $defaultB = randomFloat();
    $defaultC = randomFloat();
    $defaultAbout = '';
    $defaultTask = 'average';
    $defaultVersion = 'browser';
    $defaultSendMail = false;
    $defaultEmail = '';
}

// Для версии печати добавим класс к body
$bodyClass = ($defaultVersion === 'print') ? 'print-version' : 'browser-version';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №6 - Тестирование</title>
    <style>
        /* Общие стили */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        /* Стили формы: подписи слева, поля одинакового размера */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        .form-group label {
            width: 180px;
            font-weight: bold;
            text-align: left;
        }
        .form-group input, .form-group select, .form-group textarea {
            flex: 1;
            min-width: 200px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group textarea {
            resize: vertical;
        }
        .form-group.checkbox {
            align-items: center;
        }
        .form-group.checkbox input {
            width: auto;
            margin-left: 0;
            flex: none;
        }
        .form-group.checkbox label {
            width: auto;
            margin-left: 10px;
            font-weight: normal;
        }
        .form-group .email-field {
            display: none;
            flex: 1;
        }
        .form-group .email-field.visible {
            display: block;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .report {
            background: #e9f7e9;
            border-left: 5px solid #4CAF50;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .repeat-link {
            display: inline-block;
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
            transition: background 0.3s;
        }
        .repeat-link:hover {
            background-color: #005f73;
        }
        /* Версия для печати */
        body.print-version {
            background: white;
            padding: 0;
        }
        body.print-version .container {
            box-shadow: none;
            padding: 0;
        }
        body.print-version button,
        body.print-version .repeat-link {
            display: none;
        }
        body.print-version .form-group input,
        body.print-version .form-group select,
        body.print-version .form-group textarea {
            border: 1px solid #aaa;
            background: #fefefe;
        }
    </style>
    <script>
        // Показ/скрытие поля e-mail в зависимости от чекбокса
        function toggleEmailField() {
            var checkbox = document.getElementById('send_mail');
            var emailDiv = document.getElementById('email_field');
            if (checkbox.checked) {
                emailDiv.style.display = 'flex';
            } else {
                emailDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body class="<?php echo $bodyClass; ?>">
<div class="container">
    <h1>Лабораторная работа №6</h1>
    <h2>Математическое тестирование</h2>

    <?php
    // Выводим результаты обработки (если есть)
    if (!empty($resultMessage)) {
        echo $resultMessage;
        echo $sendMailMessage;
        if ($showRepeatLink) {
            echo '<div style="text-align: center;"><a href="' . $repeatUrl . '" class="repeat-link">Повторить тест</a></div>';
        }
    }
    ?>

    <form method="post" action="">
        <!-- ФИО -->
        <div class="form-group">
            <label>ФИО:</label>
            <input type="text" name="FIO" value="<?php echo $defaultFio; ?>" required>
        </div>
        <!-- Группа -->
        <div class="form-group">
            <label>Номер группы:</label>
            <input type="text" name="GROUP" value="<?php echo $defaultGroup; ?>" required>
        </div>
        <!-- Поля A, B, C -->
        <div class="form-group">
            <label>Значение А:</label>
            <input type="text" name="A" value="<?php echo $defaultA; ?>" required>
        </div>
        <div class="form-group">
            <label>Значение В:</label>
            <input type="text" name="B" value="<?php echo $defaultB; ?>" required>
        </div>
        <div class="form-group">
            <label>Значение С:</label>
            <input type="text" name="C" value="<?php echo $defaultC; ?>" required>
        </div>
        <!-- Ваш ответ -->
        <div class="form-group">
            <label>Ваш ответ:</label>
            <input type="text" name="USER_ANSWER" value="<?php echo isset($_POST['USER_ANSWER']) ? htmlspecialchars($_POST['USER_ANSWER']) : ''; ?>">
        </div>
        <!-- Немного о себе (многострочное) -->
        <div class="form-group">
            <label>Немного о себе:</label>
            <textarea name="ABOUT" rows="3"><?php echo $defaultAbout; ?></textarea>
        </div>
        <!-- Селектор задач (не менее 6 опций) -->
        <div class="form-group">
            <label>Выберите задачу:</label>
            <select name="TASK">
                <option value="average" <?php echo $defaultTask == 'average' ? 'selected' : ''; ?>>Среднее арифметическое (A+B+C)/3</option>
                <option value="sum" <?php echo $defaultTask == 'sum' ? 'selected' : ''; ?>>Сумма чисел A+B+C</option>
                <option value="product" <?php echo $defaultTask == 'product' ? 'selected' : ''; ?>>Произведение чисел A*B*C</option>
                <option value="max" <?php echo $defaultTask == 'max' ? 'selected' : ''; ?>>Максимальное из трёх</option>
                <option value="min" <?php echo $defaultTask == 'min' ? 'selected' : ''; ?>>Минимальное из трёх</option>
                <option value="volume" <?php echo $defaultTask == 'volume' ? 'selected' : ''; ?>>Объём параллелепипеда (A*B*C)</option>
            </select>
        </div>
        <!-- Флажок "отправить по email" с JavaScript -->
        <div class="form-group checkbox">
            <input type="checkbox" name="SEND_MAIL" id="send_mail" onclick="toggleEmailField()" <?php echo $defaultSendMail ? 'checked' : ''; ?>>
            <label>Отправить результат теста по e-mail</label>
        </div>
        <!-- Поле e-mail (изначально скрыто, показывается по чекбоксу) -->
        <div class="form-group" id="email_field" style="display: <?php echo $defaultSendMail ? 'flex' : 'none'; ?>;">
            <label>Ваш e-mail:</label>
            <input type="email" name="EMAIL" value="<?php echo $defaultEmail; ?>">
        </div>
        <!-- Селектор версии отображения -->
        <div class="form-group">
            <label>Версия отображения:</label>
            <select name="VERSION">
                <option value="browser" <?php echo $defaultVersion == 'browser' ? 'selected' : ''; ?>>Для просмотра в браузере</option>
                <option value="print" <?php echo $defaultVersion == 'print' ? 'selected' : ''; ?>>Для печати</option>
            </select>
        </div>
        <!-- Кнопка отправки -->
        <div class="form-group">
            <label></label>
            <button type="submit">Проверить</button>
        </div>
    </form>
</div>
<script>
    // Инициализация видимости поля email при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        toggleEmailField();
    });
</script>
</body>
</html>