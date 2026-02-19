<?php
$title = "Роева С.М. Группа 241-352 ЛР А-1";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>

<a href="<?php
$name = "Страница 1";
$link = "page1.php";
$current_page = false;
echo $link;
?>" <?php
if($current_page) echo 'class="selected_menu"';
?>><?php echo $name; ?></a>

<a href="<?php
$name = "Страница 2";
$link = "page2.php";
$current_page = true;
echo $link;
?>" <?php
if($current_page) echo 'class="selected_menu"';
?>><?php echo $name; ?></a>

<a href="<?php
$name = "Страница 3";
$link = "page3.php";
$current_page = false;
echo $link;
?>" <?php
if($current_page) echo 'class="selected_menu"';
?>><?php echo $name; ?></a>

</header>


<main>

<h1>Первая страница</h1>

<h2>Раздел 1</h2>
<?php
$stih = "Ветер шумит в кронах деревьев,
Тихо спит озеро в синей дали.
Солнце лениво играет на листьях,
И туман нежно ложится на скалы.

Птицы в небе расправили крылья,
Их полет — как музыка свободы.
Каждый звук природы словно откровенье,
Что открывает сердца для красоты.

Речка бежит, шепча свои тайны,
Камни её — молчаливые свидетели.
Там, где вода встречает заросли,
Скрываются истории давних лет.

Лес шумит, но в этом шуме — гармония,
Каждое дыхание земли ощущается.
И человек, пройдя тропою лесной,
Находит покой в этой вечной сказке.

Так идут дни, летят года незаметно,
Но память хранит эти моменты.
Стих живет в сердце, в мыслях, в душе,
И никогда не исчезнет, пока есть мир.";

echo nl2br($stih);
?>

<p><?php echo nl2br($stih); ?></p>

<h2>Раздел 2</h2>

<table border="1">

<?php
echo "<tr><td>Колонка 1</td><td>Колонка 2</td><td>Колонка 3</td></tr>";
?>

<tr>
<td><?php echo "Данные 1"; ?></td>
<td><?php echo "Данные 2"; ?></td>
<td><?php echo "Данные 3"; ?></td>
</tr>

</table>

<br>

<?php
echo '<img src="fotos/foto'.(date('s') % 2 + 1).'.jpg" width="300">';
?>

</main>

<footer>
<?php
echo "Сформировано ".date('d.m.Y')." в ".date('H-i:s');
?>
</footer>

</body>
</html>
