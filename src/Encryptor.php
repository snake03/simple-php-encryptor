<?php

namespace RicBarbo\SimplePHPEncryptor;

/**
 * Simple PHP Encryption Static Class.
 *
 * Encrypt and decrypt any message using two secret phrases.
 * The main advantage over the built-in encryption methods is that the encrypted message has the same length of the original
 * message, so it's often very short and it's ideal for creating authentication keys or access tokens and passing them with GET or POST requests.
 * This algorithm is very basic stuff, use it at your own risk.
 *
 *
 * @author Riccardo Barbotti <snake03@gmail.com>
 * @copyright  2016 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
class Encryptor
{

    /**
     *
     * @var string
     */
    private static $secret1 = "The Fox Jump Over The Hedge";
    private static $secret2 = "Puttin' on the Ritz";


    public static function encrypt($message) {
        $cryptable = self::$secret1 .self::$secret2;
        $seed = crc32($message) % strlen($cryptable);
        $result = chr($seed);
        for ($i = 0; $i < strlen($message); $i++) {
            $char = substr($message, $i, 1);
            $keychar = substr($cryptable, (($i + $seed) % strlen($cryptable)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    public static function decrypt($message)
    {
        $cryptable = self::$secret1 . self::$secret2;

        $string = base64_decode($message);
        $seed = ord($string);
        $string = substr($string, 1);
        $output = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($cryptable, (($i + $seed) % strlen($cryptable)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $output .= $char;
        }
        return $output;
    }

    /**
     * Sign the message
     *
     * @param $message
     * @return string
     */
    public static function sign($message)
    {
        return substr(sha1(self::$secret1 . $message . self::$secret2), 0, 20);
    }

    /**
     * Check if the signature match the message
     *
     * @param $message
     * @param $signature
     * @return bool
     */
    public static function hasValidSignature($message, $signature)
    {
        return $signature == self::sign($message);
    }

}