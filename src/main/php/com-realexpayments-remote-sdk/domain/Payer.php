<?php


namespace com\realexpayments\remote\sdk\domain;

use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\CommentCollection;


/**
 * Class Payer
 * @package com\realexpayments\remote\sdk\domain
 *
 * <p>
 * Domain object representing Payer information to be passed to Realex.
 * </p>
 *
 * <p><code><pre>
 * $payer = ( new Payer() )
 * ->addType("Business")
 * ->addRef("smithj01")
 * ->addTitle("Mr")
 * ->addFirstName("John")
 * ->addSurname("Smith")
 * ->addCompany("Acme")
 * ->addAddress($address)
 * ->addHomePhoneNumber("+35317285355")
 * ->addWorkPhoneNumber("+35317433923")
 * ->addFaxPhoneNumber("+35317893248")
 * ->addMobilePhoneNumber("+353873748392")
 * ->addEmail("jsmith@acme.com")
 * ->addComment("Comment1")
 * ->addComment("Comment2");
 *
 * </pre></code></p>
 *
 * @author vicpada
 */
class Payer {

	/**
	 * @var string The payer type can be used to identify the category of the Payer.
	 * This can be defaulted to "Business"
	 */
	private $type;

	/**
	 * @var string The payer ref is the reference for this customer. It must be unique.
	 */
	private $ref;

	/**
	 * @var string The payer’s title
	 */
	private $title;

	/**
	 * @var string  First name of payer.
	 */
	private $firstName;

	/**
	 * @var string Surname of payer
	 */
	private $surname;

	/**
	 * @var string Company Name
	 */
	private $company;

	/**
	 * @var PayerAddress object containing the payer address
	 */
	private $address;

	/**
	 * @var PhoneNumbers object containing the payer phone numbers.;
	 */
	private $phoneNumbers;

	/**
	 * @var string The payer email
	 */
	private $email;

	/**
	 * @var CommentCollection List of {@link Comment} objects to be passed in request.
	 * Optionally, up to two comments can be associated with any payer.
	 */
	private $comments;

	/**
	 * Payer constructor.
	 */
	public function __construct() {
	}

	public static function GetClassName() {
		return __CLASS__;
	}

	/**
	 * Getter for type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Setter for type
	 *
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter for ref
	 *
	 * @return string
	 */
	public function getRef() {
		return $this->ref;
	}

	/**
	 * Setter for ref
	 *
	 * @param string $ref
	 */
	public function setRef( $ref ) {
		$this->ref = $ref;
	}

	/**
	 * Getter for title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Setter for title
	 *
	 * @param string $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * Getter for firstName
	 *
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * Setter for firstName
	 *
	 * @param string $firstName
	 */
	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	/**
	 * Getter for surname
	 *
	 * @return string
	 */
	public function getSurname() {
		return $this->surname;
	}

	/**
	 * Setter for surname
	 *
	 * @param string $surname
	 */
	public function setSurname( $surname ) {
		$this->surname = $surname;
	}

	/**
	 * Getter for company
	 *
	 * @return string
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * Setter for company
	 *
	 * @param string $company
	 */
	public function setCompany( $company ) {
		$this->company = $company;
	}

	/**
	 * Getter for address
	 *
	 * @return PayerAddress
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Setter for address
	 *
	 * @param PayerAddress $address
	 */
	public function setAddress( $address ) {
		$this->address = $address;
	}

	/**
	 * Getter for phoneNumbers
	 *
	 * @return PhoneNumbers
	 */
	public function getPhoneNumbers() {
		return $this->phoneNumbers;
	}

	/**
	 * Setter for phoneNumbers
	 *
	 * @param PhoneNumbers $phoneNumbers
	 */
	public function setPhoneNumbers( $phoneNumbers ) {
		$this->phoneNumbers = $phoneNumbers;
	}

	/**
	 * Getter for email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Setter for email
	 *
	 * @param string $email
	 */
	public function setEmail( $email ) {
		$this->email = $email;
	}

	/**
	 * Getter for comments
	 *
	 * @return CommentCollection
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Setter for comments
	 *
	 * @param CommentCollection $comments
	 */
	public function setComments( $comments ) {
		$this->comments = $comments;
	}


	/**
	 * Helper method for adding a type
	 *
	 * @param string $type
	 *
	 * @return Payer
	 */
	public function addType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Helper method for adding a ref
	 *
	 * @param string $ref
	 *
	 * @return Payer
	 */
	public function addRef( $ref ) {
		$this->ref = $ref;

		return $this;
	}

	/**
	 * Helper method for adding a title
	 *
	 * @param string $title
	 *
	 * @return Payer
	 */
	public function addTitle( $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Helper method for adding a firstName
	 *
	 * @param string $firstName
	 *
	 * @return Payer
	 */
	public function addFirstName( $firstName ) {
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Helper method for adding a surname
	 *
	 * @param string $surname
	 *
	 * @return Payer
	 */
	public function addSurname( $surname ) {
		$this->surname = $surname;

		return $this;
	}

	/**
	 * Helper method for adding a company
	 *
	 * @param string $company
	 *
	 * @return Payer
	 */
	public function addCompany( $company ) {
		$this->company = $company;

		return $this;
	}

	/**
	 * Helper method for adding a address
	 *
	 * @param PayerAddress $address
	 *
	 * @return Payer
	 */
	public function addAddress( $address ) {
		$this->address = $address;

		return $this;
	}

	/**
	 * Helper method for adding a home phone number
	 *
	 * @param string $phoneNumber
	 *
	 * @return Payer
	 */
	public function addHomePhoneNumber( $phoneNumber ) {
		if ( is_null( $this->phoneNumbers ) ) {
			$this->phoneNumbers = new PhoneNumbers();
		}

		$this->phoneNumbers->setHomePhoneNumber( $phoneNumber );

		return $this;
	}

	/**
	 * Helper method for adding a work phone number
	 *
	 * @param string $phoneNumber
	 *
	 * @return Payer
	 */
	public function addWorkPhoneNumber( $phoneNumber ) {
		if ( is_null( $this->phoneNumbers ) ) {
			$this->phoneNumbers = new PhoneNumbers();
		}

		$this->phoneNumbers->setWorkPhoneNumber( $phoneNumber );

		return $this;
	}

	/**
	 * Helper method for adding a fax phone number
	 *
	 * @param string $phoneNumber
	 *
	 * @return Payer
	 */
	public function addFaxPhoneNumber( $phoneNumber ) {
		if ( is_null( $this->phoneNumbers ) ) {
			$this->phoneNumbers = new PhoneNumbers();
		}

		$this->phoneNumbers->setFaxPhoneNumber( $phoneNumber );

		return $this;
	}

	/**
	 * Helper method for adding a mobile phone number
	 *
	 * @param string $phoneNumber
	 *
	 * @return Payer
	 */
	public function addMobilePhoneNumber( $phoneNumber ) {
		if ( is_null( $this->phoneNumbers ) ) {
			$this->phoneNumbers = new PhoneNumbers();
		}

		$this->phoneNumbers->setMobilePhoneNumber( $phoneNumber );

		return $this;
	}

	/**
	 * Helper method for adding a email
	 *
	 * @param string $email
	 *
	 * @return Payer
	 */
	public function addEmail( $email ) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Helper method for adding a comment. NB Only 2 comments will be accepted by Realex.
	 *
	 * @param string $comment
	 *
	 * @return Payer
	 */
	public function addComment( $comment ) {
		//create new comments array list if null
		if ( is_null( $this->comments ) ) {
			$this->comments = new CommentCollection();
		}

		$size          = $this->comments->getSize();
		$commentObject = new Comment();
		$this->comments->add( $commentObject->addComment( $comment )->addId( ++ $size ) );

		return $this;
	}
}