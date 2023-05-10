<!DOCTYPE html>
<html>
<head>
	<title>Задание</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Список задач</h1>
	<form method="POST">
		<label  for="task_name">Название задачи:</label><br>
		<input class="textarea" type="text" id="task_name" name="task_name"><br><br>

		<table id="subtasks_table">
			<thead>
			</thead>
			<tbody>
				<tr>
					<td><input type="text" name="subtask_1"></td>
					<td><input type="number" name="hours_1"></td>
					<td><button type="button" onclick="deleteRow(this)">Удалить</button></td>
				</tr>
			</tbody>
		</table>
		<br>
		<button type="button" onclick="addRow()">Добавить подзадание</button>
		<br><br>
		<button type="submit" name="save_task">Сохранить в localstorage</button>
		<button type="button" onclick="clearForm()">Создать новую задачу</button>
	</form>

	<?php
		if (isset($_POST['save_task'])) {
			// Сохраняем задачу в файл
			$task_name = $_POST['task_name'];
			$subtasks = array();
			for ($i = 1; $i <= count($_POST)/2 - 1; $i++) {
				$subtask = array(
					"hours" => $_POST['hours_'.$i],
					"description" => $_POST['subtask_'.$i]
				);
				array_push($subtasks, $subtask);
			}
			saveTask($task_name, $subtasks);

			// Выводим сообщение об успешном сохранении
			echo "<p>Задача сохранена</p>";
		}

		function saveTask($task_name, $subtasks) {
			// Открываем файл для записи
			$file = fopen("tasks.txt", "a");

			// Записываем заголовок задачи
			fwrite($file, "+----+-------+----------------------------------------------------------------------+\n");
			fwrite($file, "|".str_pad($task_name, 67)."|\n");
			fwrite($file, "+----+-------+----------------------------------------------------------------------+\n");

			// Записываем подзадачи
			$i = 1;
			foreach ($subtasks as $subtask) {
				fwrite($file, "|".str_pad($i, 4)."|".str_pad($subtask["hours"], 7)."|".str_pad($subtask["description"], 70)."|\n");
				$i++;
			}
			// Закрываем файл
			fclose($file);
			}
			?>

			<script>
	function addRow() {
		// Находим таблицу и tbody
		var table = document.getElementById("subtasks_table");
		var tbody = table.getElementsByTagName("tbody")[0];

		// Создаем новую строку и ячейки
		var row = document.createElement("tr");
		var number_cell = document.createElement("td");
		var hours_cell = document.createElement("td");
		var description_cell = document.createElement("td");
		var delete_cell = document.createElement("td");
		var number_text = document.createTextNode(tbody.childElementCount + 1);
		var hours_input = document.createElement("input");
		hours_input.type = "number";
		hours_input.name = "hours_" + (tbody.childElementCount + 1);
		var description_input = document.createElement("input");
		description_input.type = "text";
		description_input.name = "subtask_" + (tbody.childElementCount + 1);
		var delete_button = document.createElement("button");
		delete_button.type = "button";
		delete_button.innerHTML = "Удалить";
		delete_button.onclick = function() {deleteRow(this)};

		// Добавляем ячейки в строку
		number_cell.appendChild(number_text);
		hours_cell.appendChild(hours_input);
		description_cell.appendChild(description_input);
		delete_cell.appendChild(delete_button);
		row.appendChild(number_cell);
		row.appendChild(hours_cell);
		row.appendChild(description_cell);
		row.appendChild(delete_cell);

		// Добавляем строку в tbody
		tbody.appendChild(row);
	}

	function deleteRow(button) {
		// Находим строку и удаляем ее
		var row = button.parentNode.parentNode;
		row.parentNode.removeChild(row);

		// Обновляем номера строк
		var table = document.getElementById("subtasks_table");
		var rows = table.getElementsByTagName("tr");
		for (var i = 1; i < rows.length; i++) {
			var number_cell = rows[i].getElementsByTagName("td")[0];
			number_cell.innerHTML = i;
		}
	}

	function clearForm() {
		// Очищаем поля ввода
		document.getElementById("task_name").value = "";
		var table = document.getElementById("subtasks_table");
		var tbody = table.getElementsByTagName("tbody")[0];
		while (tbody.childElementCount > 1) {
			tbody.removeChild(tbody.lastChild);
		}
	}
</script>
</body>
</html>