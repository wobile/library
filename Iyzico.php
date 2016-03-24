<?php

/**
 * Iyzico Wrapper Class
 */
class Iyzico {

    private $apiUrl = "https://iyziconnect.com/";
    private $descriptor = "AAA";
    private $status;
    private $apiId;
    private $secret;
    private $requiredParameters = array(
        "cardRegister" => array("card_number", "card_expiry_year", "card_expiry_month", "card_verification", "card_brand", "card_holder_name"),
        "cardDelete" => array("card_token"),
        "preAuth" => array("amount", "currency", "external_id", "card_token"),
        "capture" => array("amount", "currency", "transaction_id"),
        "refund" => array("amount", "currency", "transaction_id"),
        "reversal" => array("amount", "currency", "transaction_id"),
        "debit" => array("amount", "currency", "external_id", "card_token")
    );

    function __construct() {
        $settings = \MC_Helper::getSettings();
        if (isset($settings['iyzico-status'])) {
            $this->status = $settings['iyzico-status'];
            if (isset($settings['iyzico-' . $this->status . '-api-id']) && isset($settings['iyzico-' . $this->status . '-secret-key'])) {
                $this->apiId = $settings['iyzico-' . $this->status . '-api-id'];
                $this->secret = $settings['iyzico-' . $this->status . '-secret-key'];
            } else {
                throw new Exception('Iyzico için "api-id","secret-key" derlerinden biri(leri) tanımsız.');
            }
        } else {
            throw new Exception('Iyzico için statü tanımlı del.');
        }
    }

    public function cardRegister($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $result = $this->makeCall("register-card/v1/", $params);
            if ($result->response->state == "success") {
                return self::returnAsObject(array("status" => true, "token" => $result->card_token));
            } else {
                return self::returnAsObject(array("status" => false, "errors" => $result->response->error_message, "error_code" => $result->response->error_code));
            }
        } else {
            return $check;
        }
    }

    public function cardDelete($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $result = $this->makeCall("delete-card/v1/", $params);
            if ($result->response->state == "success") {
                return self::returnAsObject(array("status" => true));
            } else {
                return self::returnAsObject(array("status" => false, "errors" => $result->response->error_message, "error_code" => $result->response->error_code));
            }
        } else {
            return $check;
        }
    }

    public function preAuth($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $params["type"] = "PA";
            return $this->process($params);
        } else {
            return $check;
        }
    }

    public function capture($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $params["type"] = "CP";
            return $this->process($params);
        } else {
            return $check;
        } 
    }

    public function refund($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $params["type"] = "RF";
            return $this->process($params);
        } else {
            return $check;
        }
    }

    public function reversal($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $params["type"] = "RV";
            return $this->process($params);
        } else {
            return $check;
        } 
    }

    public function debit($params = array()) {
        if (true === $check = $this->checkParameters(__FUNCTION__, $params)) {
            $params["type"] = "DB";
            return $this->process($params);
        } else {
            return $check;
        }
    }

    private function process($params = array()) {
        $result = $this->makeCall("post/v1/", $params);
        return self::normalizeResponse($result);
    }

    private function checkParameters($method, $params) {
        if (isset($this->requiredParameters[$method])) {
            foreach ($this->requiredParameters[$method] as $param) {
                if (!isset($params[$param])) {
                    return self::returnAsObject(array("status" => false, "errors" => sprintf("%s is required parameter", $param)));
                }
            }   
            return true;
        } else {
            return self::returnAsObject(array("status" => false, "errors" => "Invalid method"));
        }
    }

    private static function normalizeResponse($result) {
        $response = array();
        if ($result->response->state == "success") {
            $response = array("status" => true);
            if (isset($result->account)) {
                $masked_card_no = null;
                if (isset($result->account->bin) && $result->account->lastfourdigits) {
                    $masked_card_no = $result->account->bin . "xxxxx" . $result->account->lastfourdigits;
                }
                $response["account"] = array(
                    "brand" => self::issetOrNull($result->account, "brand"),
                    "masked_card_no" => $masked_card_no,
                    "holder" => self::issetOrNull($result->account, "holder"),
                    "issuer_bank_name" => self::issetOrNull($result->account, "issuer_bank_name")
                );
            }
            if (isset($result->transaction)) {
                $response["transaction"] = array(
                    "transaction_id" => self::issetOrNull($result->transaction, "transaction_id"),
                    "external_id" => self::issetOrNull($result->transaction, "external_id"),
                    "reference_id" => self::issetOrNull($result->transaction, "reference_id"),
                    "state" => self::issetOrNull($result->transaction, "state"),
                    "connector_type" => self::issetOrNull($result->transaction, "connector_type"),
                    "amount" => self::issetOrNull($result->transaction, "amount"),
                    "currency" => self::issetOrNull($result->transaction, "currency")
                );
            }
        } else {
            $response = array(
                "status" => false,
                "errors" => self::issetOrNull($result->response, "error_message"),
                "error_code" => self::issetOrNull($result->response, "error_code")
            );
            if (isset($result->transaction)) {
                $response["transaction"] = array(
                    "transaction_id" => self::issetOrNull($result->transaction, "transaction_id"),
                    "external_id" => self::issetOrNull($result->transaction, "external_id"),
                    "reference_id" => self::issetOrNull($result->transaction, "reference_id"),
                    "state" => self::issetOrNull($result->transaction, "state"),
                    "connector_type" => self::issetOrNull($result->transaction, "connector_type")
                );
            }
        }
        return self::returnAsObject($response);
    }

    private static function returnAsObject($array = array()) {
        return (is_array($array)) ? (object) $array : $array;
    }

    private static function issetOrNull($object, $property) {
        return (isset($object->{$property})) ? $object->{$property} : null;
    }

    private function makeCall($endpoint = "", $params = array()) {
        $params["api_id"] = $this->apiId;
        $params["secret"] = $this->secret;
        $params["mode"] = $this->status;
        $params["response_mode"] = "SYNC";
        $params["descriptor"] = $this->descriptor;
        $params["customer_contact_ip"] = $_SERVER["REMOTE_ADDR"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return \Zend_Json::decode($result, \Zend_Json::TYPE_OBJECT);
    }

}
