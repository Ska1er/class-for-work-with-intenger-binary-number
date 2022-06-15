<?php

class BinaryNumber {

    private $number;

    function __construct (string $number){
        $this->changeValue($number);
    }

    public function getValue(): string {
        return $this->number;
    }

    public function changeValue(string $number){
        if(!BinaryNumber::isBinaryNumber($number))
            throw new Exception("Number isn't binary number");

        $this->number = trim($number);
    }

    private function ifForAloneNumber($carry, $digit): array{
        $result = "";
        if($carry){
            if($digit == "0"){
                return array (false,"1");
            }
            else $result = "0";
            return array(true, $result);
        }
        else $result = $digit;
        return array(false, $result);
    }

    public function add(BinaryNumber $addend) : BinaryNumber {
        $carry = false; 
        $result = array();
        $value = $this->getValue();
        $addendValue = $addend->getValue();
        $i = strlen($value) - 1;
        $j = strlen($addendValue) -1;

        while($i >= 0 || $j >= 0 || $carry){
            if($i >= 0 && $j >= 0){
                if($value[$i] == '1' && $addendValue[$j] == '1') {
                    if($carry)
                        $result[] = '1';
                    else $result[] = '0';
                    $carry = true;
                }
                else {
                    $result[] = strval(intval($value[$i]) +  intval($addendValue[$j]));

                    if($carry) {
                        if($result[count($result) - 1] == '0'){
                            $result[count($result) - 1] = '1';
                            $carry = false;
                        }   
                        else  $result[count($result) - 1] = '0';
                    }
                }
            }
            else if($i >= 0 || $j >= 0) {
                $arr = array();
                if($i >= 0){
                    $arr = $this->ifForAloneNumber($carry, $value[$i]);
                }
                else if($j >= 0){
                    $arr = $this->ifForAloneNumber($carry, $addendValue[$j]);
                }
                $carry = $arr[0];
                $result[] = $arr[1];

            }
            else if ($carry){
                $result[] = '1';
                $carry = false;
            }

            $i--;
            $j--;
        }

        return new BinaryNumber(implode('', array_reverse($result)));
    }

    private function findPositionOneDigit($number, $nowPosition): int{
        for($nowPosition; $nowPosition >=0; $nowPosition--){
            if($number[$nowPosition] == '1')
                break;
        }
        return $nowPosition;
    }

    public function subtract(BinaryNumber $subtrahend): BinaryNumber {
        $value = $this->getValue();
        $subrahendValue = $subtrahend->getValue();
        $i = strlen($value)-1;
        $j = strlen($subrahendValue)-1;
        $value = str_split($value);
        $subtrahendValue = str_split($subrahendValue);
        $result = array();

        while($i >= 0 || $j >=0){

            if($i >= 0 && $j >=0){
                if($value[$i] == '0' && $subtrahendValue[$j] == '1'){
                    $position = $this->findPositionOneDigit($value, $i);
                    for($k = $i; $k>$position; $k--){
                        $value[$k] = '1';
                    }
                    $value[$position] = '0';
                    $result[] = '1'; 
                }
                else{
                    $result[] = strval(intval($value[$i]) - intval($subtrahendValue[$j]));
                }
                $i--;
                $j--;
            }
            else{
                for($i; $i >= 0; $i--){
                    $result[] = $value[$i];
                }
            }
        }

        return BinaryNumber::clearLeftZeroes(new BinaryNumber(implode('',array_reverse($result))));
    }

    private function addRightZeroes(int $startPosition, string $str) : string {
        for($k = $startPosition; $k > 0; $k--){
            $str .= '0';
        }
        return $str;
    }

    public function multiply(BinaryNumber $factor): BinaryNumber {
        $value = $this->getValue();
        $factorValue = $factor->getValue();
        $i = strlen($value) - 1;
        $j = strlen($factorValue) - 1;
        $result = array();

        while($j >= 0){
            $str = "";
            if($factorValue[$j] == '0'){
                $result[] = $this->addRightZeroes($i + ($i - $j) -1, $str);
            }
            else{
                $str = $value;
                $result[] = $this->addRightZeroes(strlen($factorValue) - $j -1, $str);
            }   
            $j --;
        }

        $accurateResult = new BinaryNumber("0");
        for($k = 0; $k < count($result); $k++) {
            if(str_contains($result[$k],"1")) {
                $accurateResult = $accurateResult->add(new BinaryNumber($result[$k]));
            }    
        }
        return BinaryNumber::clearLeftZeroes($accurateResult);
    }

    public function divide(BinaryNumber $divisor): BinaryNumber {
        $divisor = BinaryNumber::clearLeftZeroes($divisor);
        if($divisor->getValue() == '0')
            throw new Exception("Сan't divide by zero");
        $compareResult = $this->compare($divisor);

        if($compareResult == -1)
            return new BinaryNumber('0');
    
        if($compareResult == 0)
            return new BinaryNumber('1');

        $value = $this->getValue();
        $divisorValue = $divisor->getValue();

        $offset = strlen($divisorValue) - 1;
        $remainder = new BinaryNumber(substr($value, 0, $offset));
        $quotient = "";

        while($offset < strlen($value)) {
            $remainder = new BinaryNumber($remainder->getValue().$value[$offset]);
            $compareResult = $remainder->compare($divisor);
            if($compareResult == 1){
                $quotient .= '0';
            }
            else {
                $quotient .= '1';
                if($compareResult == -1){
                    $remainder = $remainder->subtract($divisor);
                }
            }
            $offset +=1;
        }

        return new BinaryNumber($quotient);
    }

    public function compare(BinaryNumber $number): int {
        $valueNumber = BinaryNumber::clearLeftZeroes($number)->getValue();
        $value = BinaryNumber::clearLeftZeroes($this)->getValue();
        $lengthNumber1 = strlen($value);

        if($lengthNumber1 > strlen($valueNumber))
            return 1;
        else if ($lengthNumber1 < strlen($valueNumber))
            return -1;
        
        
        for($i = 0; $i < $lengthNumber1; $i++){
            if(intval($value[$i]) > intval($valueNumber[$i]))
                return 1;
            else if(intval($value[$i]) < intval($valueNumber[$i])) 
                return -1;
        }

        return 0;
    }

    private static function convertToNumberSystemIsExponentOfTwo(array $arrayWithNumbers, int $splitNumber, BinaryNumber $number): string {
        $numberValue = $number->getValue();
        $quotient = strlen($numberValue) % $splitNumber;

        if($quotient != 0){
            $addedString = "";
            for($i = 0; $i < $splitNumber - $quotient; $i++){
                $addedString .= '0';
            }
            $numberValue = $addedString.$numberValue;
        }
        $numberValue = str_split($numberValue, $splitNumber);
        $result ="";

        foreach($numberValue as $digit){
            $result .= $arrayWithNumbers[$digit];
        }
        
        return $result;
    }

    private static function convertFromNumberSystemIsExponentOfTwo(array $arrayWithNumbers, string $number) : BinaryNumber{
        $result = "";
        for($i = 0; $i < strlen($number); $i++){
            $result .= $arrayWithNumbers[$number[$i]];
        }
        return BinaryNumber::clearLeftZeroes(new BinaryNumber($result));
    }

    public static function convertFromBinaryToHexadecimal(BinaryNumber $number) : string {
        $hexadecimals = array(
            "0000" => "0",
            "0001" => "1",
            "0010" => "2",
            "0011" => "3",
            "0100" => "4",
            "0101" => "5",
            "0110" => "6",
            "0111" => "7",
            "1000" => "8",
            "1001" => "9",
            "1010" => "A",
            "1011" => "B",
            "1100" => "C",
            "1101" => "D",
            "1110" => "E",
            "1111" => "F"
        );

        return BinaryNumber::convertToNumberSystemIsExponentOfTwo($hexadecimals, 4, $number);
    }

    public static function convertFromHexadecimalToBinary(string $number) : BinaryNumber{
        $hexadecimals = array(
            "0" => "0000",
            "1" => "0001",
            "2" => "0010",
            "3" => "0011",
            "4" => "0100",
            "5" => "0101",
            "6" => "0110",
            "7" => "0111",
            "8" => "1000",
            "9" => "1001",
            "A" => "1010",
            "B" => "1011",
            "C" => "1100",
            "D" => "1101",
            "E" => "1110",
            "F" => "1111"
        );

        return BinaryNumber::convertFromNumberSystemIsExponentOfTwo($hexadecimals, $number);
    }

    public static function convertFromBinaryToOctal(BinaryNumber $number) : string {
        $octals = array(
            "000" => "0",
            "001" => "1",
            "010" => "2",
            "011" => "3",
            "100" => "4",
            "101" => "5",
            "110" => "6",
            "111" => "7"
        );

        return BinaryNumber::convertToNumberSystemIsExponentOfTwo($octals, 3, $number);
    }

    public static function convertFromOctalToBinary(string $number) : BinaryNumber {
        $octals = array(
            "0" => "000",
            "1" => "001",
            "2" => "010",
            "3" => "011",
            "4" => "100",
            "5" => "101",
            "6" => "110",
            "7" => "111"
        );

        return BinaryNumber::convertFromNumberSystemIsExponentOfTwo($octals, $number);
    }

    public static function convertFromBinaryToDecimal(BinaryNumber $number): int {
        $result = 0;
        $value = $number->getValue();
        $length = strlen($value);
        $exponent = $length-1;

        for($i = 0; $i<$length; $i++){
            $result +=intval($value[$i]) * pow(2, $exponent--); 
        }

        return $result;
    }

    public static function convertFromDecimalToBinary(int $number): BinaryNumber {

        if($number < 0)
            throw new Exception("Function doesn't accept negative number");
        $remainders = array();

        while($number >= 2){
            $remainders[] = $number % 2;
            $number = intval($number / 2);
        }

        $remainders[] = $number;
        $remainders = array_reverse($remainders);
        $result = implode("", $remainders);

        return new BinaryNumber($result);
    }

    public static function clearLeftZeroes(BinaryNumber $number): BinaryNumber {
        $value = $number->getValue();
        $lengthNumber = strlen($value);
        for($i = 0; $i < $lengthNumber;){
            if($value[$i] == '0' && $lengthNumber - 1 > $i)
                $i++;
            else break;
        }
        return new BinaryNumber(substr($value, $i));
    }

    public static function isBinaryNumber(string $number){
        for($i = 0; $i < strlen($number); $i++){
            if($number[$i] != '0' && $number[$i] != '1')
                return false;
        }
        return true;
    }
    
}

?>