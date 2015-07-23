<?php



namespace com\realexpayments\remote\sdk\domain;


/**
 * Interface to be implemented by all classes which represent Realex requests.
 *
 * @author vicpada
 */
interface iRequest {

	/**
	 * <p>
	 * Method returns an XML representation of the interface implementation.
	 * </p>
	 *
	 * @return string
	 */
	public function toXml();

	/**
	 * @param string $xml
	 *
	 * @return iRequest
	 */
	public function fromXml($xml);

	/**
	 * <p>
	 * Generates default values for fields such as hash, timestamp and order ID.
	 * </p>

	 * @param string $secret
	 *
	 * @return iRequest
	 */
	public function generateDefaults($secret);


	/**
	 * <p>
	 * Method returns a concrete implementation of the response class from an XML source.
	 * </p>
	 *
	 * @param string $xml
	 *
	 * @return iResponse
	 */
	public function responseFromXml($xml);

}