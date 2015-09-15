<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * <p>
 * Class representing a Comment in a Realex request.
 * </p>
 * <p>
 * Helper methods are provided (prefixed with 'add') for object creation.
 * </p>
 * <p>
 * Example creation:
 * </p>
 * <p><code><pre>
 * $comment = (new Comment())->addId(1)->addComment("My Comment");
 * </pre></code></p>
 *
 * @author vicpada
 *
 */
class Comment {

	/**
	 * @var string A free text comment
	 *
	 */
	private $comment;

	/**
	 * @var int The comment ID (1 or 2)
	 *
	 */
	private $id;

	/**
	 * Comment constructor.
	 */
	public function __construct() {
	}


	/**
	 * Helper method for adding a id
	 *
	 * @param mixed $id
	 *
	 * @return Comment
	 */
	public function addId( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * Helper method for adding a comment
	 *
	 * @param mixed $comment
	 *
	 * @return Comment
	 */
	public function addComment( $comment ) {
		$this->comment = $comment;

		return $this;
	}

	/**
	 * Getter for comment
	 *
	 * @return mixed
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * Setter for comment
	 *
	 * @param mixed $comment
	 */
	public function setComment( $comment ) {
		$this->comment = $comment;
	}

	/**
	 * Getter for id
	 *
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Setter for id
	 *
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
}