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
