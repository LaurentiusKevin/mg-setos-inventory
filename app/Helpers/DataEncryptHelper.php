<?php

namespace App\Helpers;

class DataEncryptHelper
{
    private $chiphering;
    private $encryption_iv;
    private $encryption_key;

    public function __construct()
    {
        $this->chiphering = "AES-128-CBC";
        $this->encryption_iv = "8bwGH9a5E4LxbF8X";
        $this->encryption_key = "$6by#!#G&75";
    }

    public function encryptString($string)
    {
        try {
            $options = 0;

            $encrypted = openssl_encrypt($string,$this->chiphering,$this->encryption_key,$options,$this->encryption_iv);

            return bin2hex($encrypted);
        } catch (\Throwable $th) {
            return response()->json([
                $th->getMessage(),
                $th->getFile(),
                $th->getLine(),
            ],500);
        }
    }

    public function decryptString($encrypted_string)
    {
        try {
            $options = 0;

            $encrypted_string = hex2bin($encrypted_string);

            return openssl_decrypt($encrypted_string,$this->chiphering,$this->encryption_key,$options,$this->encryption_iv);
        } catch (\Throwable $th) {
            return response()->json([
                $th->getMessage(),
                $th->getFile(),
                $th->getLine(),
            ],500);
        }
    }
}
