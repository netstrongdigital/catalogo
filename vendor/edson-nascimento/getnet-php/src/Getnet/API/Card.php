<?php
namespace Getnet\API;

/**
 * Class Card
 *
 * @package Getnet\API
 */
class Card implements \JsonSerializable {
    
    const BRAND_MASTERCARD  = "Mastercard";
    const BRAND_VISA        = "Visa";
    const BRAND_AMEX        = "Amex";
    const BRAND_ELO         = "Elo";
    const BRAND_HIPERCARD   = "Hipercard";

    private $brand;

    private $cardholder_name;

    private $expiration_month;

    private $expiration_year;

    private $number_token;

    private $security_code;

    /**
     * Card constructor.
     *
     * @param Token $card
     */
    public function __construct(Token $token) {
        $this->setNumberToken($token);
    }

    /**
     *
     * @return array
     */
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        $vars_clear = array_filter($vars, function ($value) {
            return null !== $value;
        });

        return $vars_clear;
    }
    
    /**
     * @return mixed
     */
    public function getBrand() {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand) {
        $this->brand = (string)$brand;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardholderName() {
        return $this->cardholder_name;
    }

    /**
     * @param mixed $cardholder_name
     */
    public function setCardholderName($cardholder_name) {
        $this->cardholder_name = (string)$cardholder_name;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationMonth() {
        return $this->expiration_month;
    }

    /**
     * @param mixed $expiration_month
     */
    public function setExpirationMonth($expiration_month) {
        $this->expiration_month = (string)$expiration_month;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationYear() {
        return $this->expiration_year;
    }

    /**
     * @param mixed $expiration_year
     */
    public function setExpirationYear($expiration_year) {
        $this->expiration_year = (string)$expiration_year;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberToken() {
        return $this->number_token;
    }

    /**
     * @param mixed $number_token
     */
    public function setNumberToken($token) {
        $this->number_token = (string)$token->getNumberToken();
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecurityCode() {
        return $this->security_code;
    }

    /**
     * @param mixed $security_code
     */
    public function setSecurityCode($security_code) {
        $this->security_code = (string)$security_code;
        
        return $this;
    }

}