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