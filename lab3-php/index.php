<?php
$store   = isset($_GET['store'])   ? $_GET['store']   : '';
$presses = isset($_GET['presses']) ? (int)$_GET['presses'] : 0;

if (isset($_GET['key'])) {
    $presses++;                     // любое нажатие увеличивает счётчик

    if ($_GET['key'] === 'reset') {
        $store = '';
    } else if (strlen($_GET['key']) === 1 && ctype_digit($_GET['key'])) {
        $store .= $_GET['key'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Виртуальная клавиатура — Лабораторная работа №3 — Роева София Михайловна, группа 241-352</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="display"><?php echo htmlspecialchars($store); ?></div>

        <div class="keyboard">
            <?php
            for ($i = 1; $i <= 9; $i++) {
                echo '<a href="?key=' . $i . '&store=' . urlencode($store) . '&presses=' . $presses . '" class="btn">' . $i . '</a>';
            }
            echo '<a href="?key=0&store=' . urlencode($store) . '&presses=' . $presses . '" class="btn">0</a>';
            ?>
        </div>

        <a href="?key=reset&store=<?php echo urlencode($store); ?>&presses=<?php echo $presses; ?>" class="btn reset">СБРОС</a>

        <div class="footer">
            Общее число нажатий кнопок: <strong><?php echo $presses; ?></strong>
        </div>
    </div>

</body>
</html>


