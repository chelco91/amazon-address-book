<?php

require_once("etc/apache2/data-design/encrypted-config.php");

/**
 * Small Cross Section of Amazon's Address Book
 *
 * This is a profile class for use with an Amazon account.
 * 
 *
 * @author Christopher Collopy <ccollopy@cnm.edu>
 **/
class Profile {
	/**
	 * id for this Profile; this is the primary key
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * the profile's email
	 * @var string $email
	 **/
	private $email;
	/**
	 * the profile's passwordHash
	 * @var string $passwordHash
	 **/
	private $passwordHash;

	/**
	 * constructor for this Profile
	 *
	 * @param int $newProfileId id for this Profile
	 * @param string $newEmail string containing profile's email
	 * @param string $newPasswordHash string containing profile's passwordHash
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws Exception if some other exception is thrown
	 **/
	public function __construct($newProfileId, $newEmail, $newPasswordHash) {
		try {
			$this->setProfileId($newProfileId);
			$this->setEmail($newEmail);
			$this->setPasswordHash($newPasswordHash);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// rethrow the generic exception to the caller
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 *
	 * @return int value of profile id
	 **/
	public function getProfileId() {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int $newProfileId new value of profile id
	 * @throws InvalidArgumentException if $newProfileId is not an integer
	 * @throws RangeException if $newProfileId is not positive
	 **/
	public function setProfileId($newProfileId) {
		// base case: if the profileId is null, this is a new profile without a mySQL assigned id (yet)
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		// verify the profile id is valid
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("profile id is not a valid integer"));
		}

		// verify the profile id is positive
		if($newProfileId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}

		// convert and store the profileId
		$this->profileId = intval($newProfileId);
	}

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getEmail() {
		return ($this->email);
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newEmail new value of email
	 * @throws InvalidArgumentException if $newEmail is empty or not a string
	 * @throws RangeException if $newEmail is > 140 characters
	 **/
	public function setEmail($newEmail) {
		// verify the email content is a string
		$newEmail = trim($newEmail);
		$newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newEmail) === true) {
			throw(new InvalidArgumentException("email is empty or not a string"));
		}

		// verify the email will fit in the database
		if(strlen($newEmail) > 128) {
			throw(new RangeException("email too large"));
		}

		// store the email
		$this->email = $newEmail;
	}

	/**
	 * accessor method for passwordHash
	 *
	 * @return string value of passwordHash
	 **/
	public function getPasswordHash() {
		return ($this->passwordHash);
	}

	/**
	 * mutator method for passwordHash
	 *
	 * @param string $passwordHash new value of passwordHash
	 * @throws UnexpectedValueException if $newPasswordHash is not a hexadecimal string
	 * @throws RangeException if $newPasswordHash is not exactly equal to 128 characters
	 **/
	public function setPasswordHash($newPasswordHash) {
		// verify the passwordHash content is a hexadecimal string
		if(!ctype_xdigit($newPasswordHash)) {
			throw(new UnexpectedValueException("passwordHash is not a hexadecimal string"));
		}

		// verify the passwordHash is the right size
		if(strlen($newPasswordHash) > 128 || strlen($newPasswordHash) < 128) {
			throw(new RangeException("passwordHash is not correct size"));
		}

		// store the passwordHash
		$this->passwordHash = $newPasswordHash;
	}

	/************************************* mySQL *************************************************************/


	/**
	 * inserts this Profile into mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// connect to mySQL
		try {
			$pdo = connectToEncryptedMySQL("etc/apache2/data-design/ccollopy.ini");
		} catch(PDOException $pdoException) {
			// handle PDO errors
		} catch(Exception $exception) {
			// handle other errors
		}

		// enforce the profileId is null (i.e., don't insert a profile that already exists)
		if($this->profileId !== null) {
			throw(new PDOException("not a new profile"));
		}

		// create query template
		$query = "INSERT INTO profile(profileId, email, passwordHash) VALUES(:profileId, :email, :passwordHash)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = array("profileId" => $this->profileId, "email" => $this->email, "passwordHash" => passwordHash);
		$statement->execute($parameters);

		// update the null profileId with what mySQL just gave us
		$this->profileId = intval($pdo->lastInsertId());
	}


	/**
	 * deletes this Profile from mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {
		// connect to mySQL
		try {
			$pdo = connectToEncryptedMySQL("etc/apache2/data-design/ccollopy.ini");
		} catch(PDOException $pdoException) {
			// handle PDO errors
		} catch(Exception $exception) {
			// handle other errors
		}

		// enforce the profileId is not null (i.e., don't delete a profile that hasn't been inserted)
		if($this->profileId === null) {
			throw(new PDOException("unable to delete a profile that does not exist"));
		}

		// create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = array("profileId" => $this->profileId);
		$statement->execute($parameters);
	}

	/**
	 * updates this Profile in mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function update(PDO &$pdo) {
		// connect to mySQL
		try {
			$pdo = connectToEncryptedMySQL("etc/apache2/data-design/ccollopy.ini");
		} catch(PDOException $pdoException) {
			// handle PDO errors
		} catch(Exception $exception) {
			// handle other errors
		}

		// enforce the profileId is not null (i.e., don't update a profile that hasn't been inserted)
		if($this->profileId === null) {
			throw(new PDOException("unable to update a profile that does not exist"));
		}

		// create query template
		$query = "UPDATE profile SET email = :email, passwordHash = :passwordHash WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = array("profileId" => $this->profileId, "email" => $this->email, "passwordHash" => $this->passwordHash);
		$statement->execute($parameters);
	}
}