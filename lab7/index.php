<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Ввод массива. Лаб 7. Роева 241-352.</title>

<script>
function setHTML(element, txt) {
    if (element.innerHTML !== undefined)
        element.innerHTML = txt;
    else {
        var range = document.createRange();
        range.selectNodeContents(element);
        range.deleteContents();
        var fragment = range.createContextualFragment(txt);
        element.appendChild(fragment);
    }
}

function addElement() {
    var table = document.getElementById("elements");
    var index = table.rows.length;

    var row = table.insertRow(index);

    var cellIndex = row.insertCell(0);
    var cellInput = row.insertCell(1);

    setHTML(cellIndex, index);
    setHTML(cellInput, '<input type="text" name="element' + index + '">');

    document.getElementById("arrLength").value = table.rows.length;
}
</script>

<style>
.element_row {
    padding: 5px;
}
</style>

</head>
<body>

<h2>Введите элементы массива</h2>

<form action="sort.php" method="POST" target="_blank">

<table id="elements" border="1">
<tr>
    <td>0</td>
    <td><input type="text" name="element0"></td>
</tr>
</table>

<input type="hidden" id="arrLength" name="arrLength" value="1">

<br>

<select name="algoritm">
    <option value="0">Сортировка выбором</option>
    <option value="1">Пузырьковая сортировка</option>
    <option value="2">Сортировка Шелла</option>
    <option value="3">Гномья сортировка</option>
    <option value="4">Быстрая сортировка</option>
    <option value="5">Встроенная sort()</option>
</select>

<br><br>

<input type="button" value="Добавить еще элемент" onclick="addElement()">
<input type="submit" value="Сортировать массив">

</form>

</body>
</html>