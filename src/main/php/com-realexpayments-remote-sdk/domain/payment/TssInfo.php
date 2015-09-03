<?php


namespace com\realexpayments\remote\sdk\domain\payment;



/**
 * <p>
 * Domain object representing TSS (realscore) information to be passed to Realex.
 * Realscore is a real time transaction screening and data checking system to assist a merchant
 * with the identification of potentially high-risk transactions.
 * </p>
 *
 * <p><code><pre>
 * $tssInfo = (new TssInfo())
 *     ->addCustomerNumber("customer number")
 *     ->addProductId("product ID")
 *     ->addVariableReference("variable ref")
 *     ->addCustomerIpAddress("127.0.0.1")
 *     ->addAddress((new Address())
 *           ->addType(AddressType::BILLING)
 *           ->addCode("digitsFromPostcode|digitsFromAddressLineOne")
 *           ->addCountry("countryCode"))
 *    ->addAddress((new Address())
 *           ->addType(AddressType::SHIPPING)
 *           ->addCode("digitsFromPostcode|digitsFromAddressLineOne")
 *           ->addCountry("countryCode"));
 * </pre></code></p>
 *
 * @author vicpada
 *
 */
class TssInfo {

	/**
	 * @var string  The number you assign to the customer. This can allow checking of previous transactions
	 * by this customer.
	 *
	 */
	private $customerNumber;

	/**
	 * @var string The product code you assign to the product.
	 *
	 */
	private $productId;

	/**
	 * @var string Any reference you also would like to assign to the customer. This can allow checking,
	 * using realscore, of previous transactions by this customer.
	 *
	 */
	private $variableReference;

	/**
	 * @var string  The IP address of the customer.
	 *
	 */
	private $customerIpAddress;

	/**
	 * @var Address[] The address of the customer.
	 *
	 */
	private $addresses;

	/**
	 * Getter for the customer number
	 *
	 * @return string
	 */
	public function getCustomerNumber() {
		return $this->customerNumber;
	}

	/**
	 * Getter for the customer number
	 *
	 * @param string $customerNumber
	 */
	public function setCustomerNumber( $customerNumber ) {
		$this->customerNumber = $customerNumber;
	}

	/**
	 * Getter for the product id
	 *
	 * @return string
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * Setter for the product id
	 *
	 * @param string $productId
	 */
	public function setProductId( $productId ) {
		$this->productId = $productId;
	}

	/**
	 * Getter for the variable reference
	 *
	 * @return string
	 */
	public function getVariableReference() {
		return $this->variableReference;
	}

	/**
	 * Setter for the variable refernce
	 *
	 * @param string $variableReference
	 */
	public function setVariableReference( $variableReference ) {
		$this->variableReference = $variableReference;
	}

	/**
	 * Getter for the customer ip address
	 *
	 * @return string
	 */
	public function getCustomerIpAddress() {
		return $this->customerIpAddress;
	}

	/**
	 * Setter for the customer ip address
	 *
	 * @param string $customerIpAddress
	 */
	public function setCustomerIpAddress( $customerIpAddress ) {
		$this->customerIpAddress = $customerIpAddress;
	}

	/**
	 * Getter for addresses
	 *
	 * @return Address[]
	 */
	public function getAddresses() {
		return $this->addresses;
	}

	/**
	 * Setter for address list.
	 *
	 * @param Address[] $addresses
	 */
	public function setAddresses( array $addresses ) {
		$this->addresses = $addresses;
	}


	/**
	 * Helper method for adding a customer number.
	 *
	 * @param string $customerNumber
	 * @return $this
	 */
	public function addCustomerNumber( $customerNumber ) {
		$this->customerNumber = $customerNumber;

		return $this;
	}

	/**
	 * Helper method for adding a product ID.
	 *
	 * @param string $productId
	 * @return $this
	 *
	 */
	public function addProductId( $productId ) {
		$this->productId = $productId;

		return $this;
	}

	/**
	 * Helper method for adding a variable reference.
	 *
	 * @param string $variableReference
	 * @return $this
	 */
	public function addVariableReference( $variableReference ) {
		$this->variableReference = $variableReference;

		return $this;
	}

	/**
	 * Helper method for adding a customer IP address.
	 *
	 * @param string $customerIpAddress
	 * @return $this
	 */
	public function addCustomerIpAddress( $customerIpAddress ) {
		$this->customerIpAddress = $customerIpAddress;

		return $this;
	}

	/**
	 * Helper method for adding an address.
	 *
	 * @param Address $address
	 * @return $this
	 *
	 */
	public function addAddress( Address $address ) {

		if ( is_null( $this->addresses ) ) {
			$this->addresses = array();
		}

		$this->addresses[] = $address;

		return $this;
	}

}