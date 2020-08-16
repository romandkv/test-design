# test-design

## 1) Возможно ли такое в PHP и как это реализуется, если возможно:
```php
$obj = new Building();
$obj['name'] = 'Main tower';
$obj['flats'] = 100;
$obj->save();
```

Возможно, если реализовать интерфейс **ArrayAccess**:
```php
class Building implements ArrayAccess {
    
    public $name;
    public $flats;
    
    public function offsetExists($offset) {
        return isset($this->$offset);
    }
    public function offsetGet($offset) {
        return $this->$offset ?? null;
    }
    
    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }
    
    public function offsetUnset($offset) {
        unset($this->$offset);
    }
    
    public function save() {
        /**/
    }
}
```
## 2) Реализовать метод, который будет проверять, соответствует ли дата, хранимая в переменной $str, определенному формату $format? Используем описание формата такое же, как в стандартных функциях php (date, …). Пример описания формата:
```php
<?php
function checkDateByFormat($date, $format) {
    return DateTime::createFromFormat($format, $date) ? true : false;
}

var_dump(checkDateByFormat(date('m:d:Y'), 'm:d:Y'));
```
## 3) Есть две сущности: сотрудник компании, соискатель. Обе сущности хранятся в одной таблице «специалисты» с полями: имя, резюме, дата отправки резюме, дата приема на работу, должность.
## Соискатель до момента приема на работу имеет: имя, резюме, дату отправки резюме. После приема на работу у него появляются: должность, дата приема на работу.

## Используя ООП, написать реализацию этих двух сущностей. В классе «Соискатель» должна быть возможность принять сотрудника на работу.
```php
<?php

abstract class Specialist {

	protected string 	$name;
	protected string 	$dateOfSendSummary;
	protected 			$summary;

	public function __construct(
		string $name,
		string $dateOfSendSummary,
		$summary
	) {
		$this->name 				= $name;
		$this->dateOfSendSummary 	= $dateOfSendSummary;
		$this->summary 				= $summary;
	}

}

class Employee extends Specialist {

	protected string $dateOfEntry;
	protected string $position;

	public function __construct(
		string $name,
		string $dateOfSendSummary,
		$summary,
		string $dateOfEntry,
		string $position
	) {
		parent::__construct(
			$name,
			$dateOfSendSummary,
			$summary
		);
		$this->dateOfEntry 	= $dateOfEntry;
		$this->position 	= $position;
	}
}


class Applicant extends Specialist {

	public function __construct(
		string $name,
		string $dateOfSendSummary,
		$summary
	) {
		parent::__construct(
			$name,
			$dateOfSendSummary,
			$summary
		);
	}

	public function becomeEmployee(string $dateOfEntry, string $position) {
		return new Employee(
			$this->name,
			$this->dateOfSendSummary,
			$this->summary,
			$dateOfEntry,
			$position
		);
	}

}
```
## 4) Ниже приведён пример плохого кода. Представьте, что вы вернулись в 2010 год и решили отрефакторить его с учетом текущей ветки языка. Опишите проблемы этого кода и приведите пример хорошей реализации:
```php
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

```
https://qna.habr.com/q/263613 - чем плох $_REQUEST
Все данные следует приводить к их типам и экранировать строки во избежание попадания ковычек 
и прочих символов, которые являются ключевыми при построении запроса, при вставке в строковый запрос
При выводе данных на страницу, следует применять htmlspecialchars(), чтобы избежать попадания в html код
html сущностей, выводиться должен только контент
```php
<?php
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


}
```

## 5) Расскажите, какой результат выведет следующий код и почему:

Ответ: 150;
Дело в приоритете исполнения методов(переопределенного, из трейта, и метода предка):
Наивысший приоритет имеет метод переопределенный, далее из трейта, метод предка -
следовательно 
Calculator1::calculate вернет 5,
Calculator2::calculate вернет 10,
Calculator3::calculate вернет 20

```php
abstract class BaseCalculator {
    public function calculate($val1, $val2) { return ($val1 + $val2) * 2; }
}

trait CalculatorTrait {
    public function calculate($val1, $val2) { return $val1 + $val2; }
}

class Calculator1 extends BaseCalculator {
    use CalculatorTrait;

    public function calculate($val1, $val2) { return ($val1 + $val2) / 2; }
}

class Calculator2 extends BaseCalculator {
    use CalculatorTrait;
}

class Calculator3 extends BaseCalculator { }


$val1 = 3;
$val2 = 7;

$calc1 = new Calculator1();
$calc2 = new Calculator2();
$calc3 = new Calculator3();

echo $calc1->calculate($val1, $val2) * ($calc2->calculate($val1, $val2) + $calc3->calculate($val1, $val2));
```

## 6) Файл /tmp/large_file.txt содержит около 5 000 000 строк, в каждой строке - не более 7 символов. Нижеприведенный код использует более 40 Мб оперативной памяти для чтения, обработки и вывода данных из файла в буфер. Измените функцию readMyFile таким образом, чтобы потребление памяти сократилось в 2 и более раз (можно использовать версию PHP 5.5 и выше):

```
Memory usage is: 28
Memory usage is: 0
Speed = INF *Код выполняет свою задачу*
```
```php
function readTheFile(&$memoryUsage) {
    $begin = memory_get_usage(true);
    $handle = fopen('large_file.txt', "r");
    while ($line = fread($handle, 7)) {
    	yield $line;
    }
    fclose($handle);
	$end = memory_get_usage(true);
    $memoryUsage = $end - $begin;
}

function readMyFile(&$memoryUsage)
{
    $begin = memory_get_usage(true);
    $filePath = 'large_file.txt';
    $result = [];
    foreach (file($filePath) as $x => $line) {
        $result[] = 'Line ' . $x . ': ' . $line;
    }
    $end = memory_get_usage(true);
    $memoryUsage = $end - $begin;
    return $result;
}

$memoryUsage = 0;
$memoryUsage2 = 0;

readMyFile($memoryUsage);
foreach (readTheFile($memoryUsage2) as $item) {

};

$memoryUsage = $memoryUsage / 1024 / 1024;
$memoryUsage2 = $memoryUsage2 / 1024 / 1024;


echo "Memory usage is: $memoryUsage<br>";
echo "Memory usage is: $memoryUsage2<br>";

echo "Mem1/Mem2 = " . $memoryUsage / $memoryUsage2 . "<br>";
```

# MYSQL
## 1) Существует таблица, в которой хранятся записи о неких событиях (например, выставки или фестивали). Необходимо написать код, который выводил бы на экран события, которые проходят на этой неделе.
### Данные в таблице описаны следующими полями:
```
id int not null primary key
name text
begin_date datetime // дата начала события
end_date datetime // дата окончания события
```

```php

try {
	$dbh = new PDO(
		'mysql:dbname=db_name;host=localhost', 
		'логин', 
		'пароль'
	);
	$stmt = $dbh->query(
		'SELECT name, begin_date, end_date
		FROM events 
		WHERE WEEK(begin_date) = WEEK(CURDATE())'
	);
	while ($row = $stmt->fetch()) {
		echo 
			htmlspecialchars($row['name']) .
			htmlspecialchars($row['begin_date']) .
			htmlspecialchars($row['end_date']) .
			"\n";
	}

} catch (PDOException $e) {
	die($e->getMessage());
}
```

## 2)  Каждый семинар характеризуется следующими атрибутами: название, дата начала, дата окончания, город, участники события. Необходимо спроектировать структуру таблиц БД для хранения записей о таких семинарах.

В самом общем случае в одном семинаре может принимать участие множество людей, и один человек может принимать участие в нескольких семинарах.
Отношение многие ко многим, потребуется 3 таблицы
1) 
```
SEMINAR
------------------------------
id int not null primary key
name varchar   
city varchar
begin_date datetime
end_date datetime
------------------------------


PARTICIPATOR
------------------------------
id int not null primary key
....
------------------------------

SEMINAR_PARTICIPATOR (Каждое поле внешний ключ на id из соотвествующей таблицы)
-----------------------------------------
id_seminar int not null primary key 
id_participator int not null primary key
-----------------------------------------
```

## 3) Предложить структуру для хранения древовидных комментариев.
COMMENT
----------------------
id int not null primary key
text varchar
id_user // внешний ключ на id user'a
id_previous_level // внешний ключ на id комментария из таблицы COMMENT (NULL если комментарий не имеет предыдущего уровня)
------------------------
