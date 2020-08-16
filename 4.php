<?php
// получить дату вставки новостей
$insert = $_REQUEST['insert'];
$insertTs = strtotime($insert);


$sql = "SELECT * FROM news WHERE insert = '" . $insert . "'";
$res = mysql_query($sql);
while ($item = mysql_fetch_assoc($res)) {
	// перебираем новости и для каждой новости отображаем ее анонс
	echo 'News ' . $item['title'] . ': ' . "\n";
	$sql = 'SELECT * FROM announce WHERE item_id = ' . $item['id'];
	$res2 = mysql_query($sql);
	while ($item = mysql_fetch_assoc($res2)) {
		echo $announce['text'] . "\n";
		if ($item['is_new']) {
			$mainItem = $item;
		}
	}
	echo 'Main news item: ' . $mainItem['id'] . "\n";
}

echo date('Y-m-d H:i:s', $insertTs);


// получить дату вставки новостей
//$insert = $_REQUEST['insert'];
$insert = $_POST['insert'];
$insertTs = strtotime($insert);

if ($insert !== -1) {
	$sql = "SELECT * FROM news WHERE insert = '" . mysql_real_escape_string($insert) . "'";
	$res = mysql_query($sql);
	while ($item = mysql_fetch_assoc($res)) {
		// перебираем новости и для каждой новости отображаем ее анонс
		echo 'News ' . htmlspecialchars($item['title']) . ': ' . "\n";
		$item['id'] = intval($item['id']);
		if ($item['id'] !== 0) {
			$sql = 'SELECT * FROM announce WHERE item_id = ' . "'" . $item['id'] . "'";
			$res2 = mysql_query($sql);
			while ($item = mysql_fetch_assoc($res2)) {
				echo htmlspecialchars($announce['text']) . "\n";
				if ($item['is_new']) {
					$mainItem = $item;
				}
			}
			echo 'Main news item: ' . htmlspecialchars($mainItem['id']) . "\n";
		}
	}
	echo date('Y-m-d H:i:s', $insertTs);
}
