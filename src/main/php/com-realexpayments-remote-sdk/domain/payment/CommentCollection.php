<?php


namespace com\realexpayments\remote\sdk\domain\payment;

use Doctrine\OXM\Mapping as DOM;

/**
 * Class CommentCollection, used to hold a collection of comments
 *
 * @package com\realexpayments\remote\sdk\domain\payment
 * @author vicpada
 * @Dom\XmlEntity(xml="comments")
 */
class CommentCollection {


	/**
	 * @var Comment[] List of {@link Comment} objects to be passed in request. Optionally, up to two comments
	 * can be associated with any transaction.
	 *
	 * @Dom\XmlElement(type="com\realexpayments\remote\sdk\domain\payment\Comment", collection=true, direct=true, name="comments")
	 */
	private $comments;

	/**
	 * CommentCollection constructor.
	 *
	 */
	public function __construct() {
		$this->comments = array();
	}

	/**
	 * Getter for comments
	 *
	 * @return Comment[]
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Setter for comments
	 *
	 * @param Comment[] $comments
	 */
	public function setComments( $comments ) {
		$this->comments = $comments;
	}

	/**
	 * Get Comment at index
	 *
	 * @param $index
	 *
	 * @return Comment
	 */
	public function get( $index ) {
		return $this->comments[ $index ];
	}


	/**
	 * Set Comment at index
	 *
	 * @param $index
	 * @param Comment $value
	 */
	public function set( $index, Comment $value ) {
		$this->comments[ $index ] = $value;
	}

	/**
	 * Add a new Comment
	 *
	 * @param Comment $value
	 */
	public function add( Comment $value ) {
		$this->comments[] = $value;
	}

	public function getSize() {
		return count( $this->comments );
	}
}