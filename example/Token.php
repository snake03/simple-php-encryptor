<?php

require_once('../src/Encryptor.php');
use RicBarbo\SimplePHPEncryptor\Encryptor;


/**
 * Created by PhpStorm.
 * User: riccardobarbotti
 * Date: 28/07/16
 * Time: 17:19
 */
class Token
{
    public $id;
    public $timestamp;
    public $signature;
    public $message;
    public $encrypted_message;

    private $token_duration = 604800; //1 settimana;
    private $separator = "#";

    public function __construct($encrypted_message = null)
    {
        $this->encrypted_message = $encrypted_message;
    }

    /**
     * Decripta il token
     *
     * @return $this
     */
    public function decrypt()
    {
        $this->message = Encryptor::decrypt($this->encrypted_message);
        $this->extractElements();
        return $this;
    }

    /**
     * Estrai gli elementi rilevanti da un token decriptato
     *
     * @return $this
     */
    protected function extractElements()
    {
        $elements = explode('#', $this->message);
        if (count($elements) != 3)
            return false;

        $this->id = $elements[0];
        $this->timestamp = $elements[1];
        $this->signature = $elements[2];

        return $this;
    }

    /**
     * Cripta il token
     *
     * @return $this
     */
    public function encrypt()
    {
        $message = $this->calculateMessage();
        $this->encrypted_message = Encryptor::encrypt($message);
        return $this;
    }

    /**
     * Restituisci il messaggio del token
     *
     * @return string
     */
    private function calculateMessage()
    {
        return
            $this->payload() .
            $this->separator .
            $this->getSignature();
    }

    /**
     * Restituisci le informazioni concatenate rilevanti del token
     *
     * @return string
     */
    private function payload()
    {
        return $this->id . $this->separator . $this->timestamp;
    }

    /**
     * Calcola la firma del token
     *
     * @return string
     */
    private function getSignature()
    {
        return Encryptor::sign($this->payload());
    }

    /**
     * Controlla se il token non è scaduto e ha una firma valida
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->hasValidSignature() && !$this->isExpired();
    }

    /**
     * Controlla se la firma del token è valida
     * @return bool
     */
    public function hasValidSignature()
    {
        return $this->signature == $this->getSignature();
    }

    /**
     * Controlla se il token è scaduto
     *
     * @return bool
     */
    public function isExpired()
    {
        return ($this->timestamp + $this->token_duration) < time();
    }

    public function generateForUser($user)
    {
        $this->id = $user['id'];
        $this->timestamp = time();
        return $this;
    }

    public function getEncryptedMessage()
    {
        return $this->encrypted_message;
    }

    public function validUntil()
    {
        return date('Y-m-d H:i:s', $this->timestamp + $this->token_duration);
    }
}