<?php

namespace App\Services\Utils;

class CodeGeneratorService
{
    private string $alphanumeric = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private string $numeric = '0123456789';

    private string $alphabetic = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private string $especialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    public function generate(string $type, int $length = 10): string
    {
        if ($type === 'alphanumeric') {
            return $this->generateAlphanumeric($length);
        } elseif ($type === 'numeric') {
            return $this->generateNumeric($length);
        } elseif ($type === 'alphabetic') {
            return $this->generateAlphabetic($length);
        } elseif ($type === 'special') {
            return $this->generateSpecial($length);
        } else {
            throw new \InvalidArgumentException("Invalid type: $type");
        }
    }

    public function generateAlphanumeric(int $length = 10): string
    {
        $charactersLength = strlen($this->alphanumeric);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $this->alphanumeric[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function generateNumeric(int $length = 10): string
    {
        $charactersLength = strlen($this->numeric);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $this->numeric[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function generateAlphabetic(int $length = 10): string
    {
        $charactersLength = strlen($this->alphabetic);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $this->alphabetic[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function generateSpecial(int $length = 10): string
    {
        $charactersLength = strlen($this->especialChars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $this->especialChars[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
