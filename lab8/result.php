<?php
function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function splitChars($text)
{
    if ($text === '') {
        return array();
    }

    return preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: array();
}

function lowerChar($char)
{
    $map = array(
        'А' => 'а', 'Б' => 'б', 'В' => 'в', 'Г' => 'г', 'Д' => 'д', 'Е' => 'е', 'Ё' => 'ё',
        'Ж' => 'ж', 'З' => 'з', 'И' => 'и', 'Й' => 'й', 'К' => 'к', 'Л' => 'л', 'М' => 'м',
        'Н' => 'н', 'О' => 'о', 'П' => 'п', 'Р' => 'р', 'С' => 'с', 'Т' => 'т', 'У' => 'у',
        'Ф' => 'ф', 'Х' => 'х', 'Ц' => 'ц', 'Ч' => 'ч', 'Ш' => 'ш', 'Щ' => 'щ', 'Ъ' => 'ъ',
        'Ы' => 'ы', 'Ь' => 'ь', 'Э' => 'э', 'Ю' => 'ю', 'Я' => 'я'
    );

    if (isset($map[$char])) {
        return $map[$char];
    }

    return strtolower($char);
}

function lowerText($text)
{
    $result = '';

    foreach (splitChars($text) as $char) {
        $result .= lowerChar($char);
    }

    return $result;
}

function charCount($text)
{
    return count(splitChars($text));
}

function regexCount($pattern, $text)
{
    return preg_match_all($pattern, $text, $matches) ?: 0;
}

function describeChar($char)
{
    if ($char === ' ') {
        return '[пробел]';
    }

    if ($char === "\n") {
        return '[перенос строки]';
    }

    if ($char === "\r") {
        return '[возврат каретки]';
    }

    if ($char === "\t") {
        return '[табуляция]';
    }

    return $char;
}

function compareWords($left, $right)
{
    $leftLower = lowerText($left);
    $rightLower = lowerText($right);

    $leftCp = iconv('UTF-8', 'CP1251//IGNORE', $leftLower);
    $rightCp = iconv('UTF-8', 'CP1251//IGNORE', $rightLower);

    if ($leftCp !== false && $rightCp !== false) {
        return strcmp($leftCp, $rightCp);
    }

    return strcmp($leftLower, $rightLower);
}

function analyzeText($text)
{
    $chars = splitChars($text);
    $symbolStats = array();

    foreach ($chars as $char) {
        $key = lowerChar($char);
        if (isset($symbolStats[$key])) {
            $symbolStats[$key]++;
        } else {
            $symbolStats[$key] = 1;
        }
    }

    uksort($symbolStats, 'compareWords');

    preg_match_all('/[\p{L}\p{N}]+(?:[-\'][\p{L}\p{N}]+)*/u', $text, $wordMatches);
    $wordStats = array();

    foreach ($wordMatches[0] as $word) {
        $normalized = lowerText($word);
        if (isset($wordStats[$normalized])) {
            $wordStats[$normalized]++;
        } else {
            $wordStats[$normalized] = 1;
        }
    }

    uksort($wordStats, 'compareWords');

    return array(
        'char_total' => charCount($text),
        'letters' => regexCount('/\p{L}/u', $text),
        'lowercase' => regexCount('/\p{Ll}/u', $text),
        'uppercase' => regexCount('/\p{Lu}/u', $text),
        'punctuation' => regexCount('/\p{P}/u', $text),
        'digits' => regexCount('/\p{N}/u', $text),
        'words_total' => count($wordMatches[0]),
        'symbols' => $symbolStats,
        'words' => $wordStats
    );
}

$text = $_POST['data'] ?? '';
$trimmed = trim($text);
$stats = null;

if ($trimmed !== '') {
    $stats = analyzeText($text);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЛР8. Результат анализа</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page">
        <header class="page-header">
            <div>
                <h1>Результат анализа текста</h1>
                <p>UTF-8, русский и английский текст обрабатываются в одном интерфейсе.</p>
            </div>
            <a href="index.html" class="back-link">Другой анализ</a>
        </header>

        <main class="panel">
            <?php if ($stats === null): ?>
                <div class="empty-state">Нет текста для анализа</div>
            <?php else: ?>
                <section class="source-text">
                    <?php echo nl2br(h($text)); ?>
                </section>

                <table class="result-table">
                    <tbody>
                        <tr><th>Количество символов (с пробелами)</th><td><?php echo h($stats['char_total']); ?></td></tr>
                        <tr><th>Количество букв</th><td><?php echo h($stats['letters']); ?></td></tr>
                        <tr><th>Количество строчных букв</th><td><?php echo h($stats['lowercase']); ?></td></tr>
                        <tr><th>Количество заглавных букв</th><td><?php echo h($stats['uppercase']); ?></td></tr>
                        <tr><th>Количество знаков препинания</th><td><?php echo h($stats['punctuation']); ?></td></tr>
                        <tr><th>Количество цифр</th><td><?php echo h($stats['digits']); ?></td></tr>
                        <tr><th>Количество слов</th><td><?php echo h($stats['words_total']); ?></td></tr>
                    </tbody>
                </table>

                <div class="columns">
                    <section class="table-card">
                        <h2>Вхождения символов</h2>
                        <table class="result-table compact">
                            <thead>
                                <tr>
                                    <th>Символ</th>
                                    <th>Количество</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['symbols'] as $symbol => $count): ?>
                                    <tr>
                                        <td><?php echo h(describeChar($symbol)); ?></td>
                                        <td><?php echo h($count); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>

                    <section class="table-card">
                        <h2>Список слов и количество вхождений</h2>
                        <table class="result-table compact">
                            <thead>
                                <tr>
                                    <th>Слово</th>
                                    <th>Количество</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['words'] as $word => $count): ?>
                                    <tr>
                                        <td><?php echo h($word); ?></td>
                                        <td><?php echo h($count); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>