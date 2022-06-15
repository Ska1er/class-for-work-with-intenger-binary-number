<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require __DIR__.'/../BinaryNumber.php';

final class BinaryNumberTest extends TestCase
{
    //Testing method ConvertFromDecimalToBinary

    public function testConvertFromDecimalToBinary_PositiveNumber(): void {
        $result = BinaryNumber::convertFromDecimalToBinary(4);

        $this->assertEquals('100', $result->getValue());
    }

    public function testConvertFromDecimalToBinary_NegativeNumber(): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Function doesn't accept negative number");

        BinaryNumber::convertFromDecimalToBinary(-4);
    }

    public function testConvertFromDecimalToBinary_Zero(): void {
        $result = BinaryNumber::convertFromDecimalToBinary(0);

        $this->assertEquals('0', $result->getValue());
    }

    //Testing construct

    public function testCreateBinaryNumber_BinaryNumberParam() : void {
        $number = new BinaryNumber('1001');

        $this->assertIsObject($number);
    }

    public function testCreateBinaryNumber_NotBinaryNumberParam() : void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Number isn't binary number");

        new BinaryNumber('hello world');

    }


    //Testing method getValue 

    public function testGetValue(): void {
        $number = new BinaryNumber('1001');
        $value = $number->getValue();

        $this->assertIsString($value);
        $this->assertEquals('1001', $value);
    }

    //Testing method changeValue

    public function testChangeValue_SetBinaryNumber() : void {
        $number = new BinaryNumber('1001');
        $number->changeValue('1');
        $value = $number->getValue();

        $this->assertIsString($value);
        $this->assertEquals('1', $value);
    }

    public function testChangeValue_SetNotBinaryNumber() : void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Number isn't binary number");

        $number = new BinaryNumber('1001');
        $number->changeValue('Hello world');
    }

    //Testing method ConvertFromBinaryToDecimal

    public function testConvertFromBinaryToDecimal_PositiveNumber(): void {
        $result = BinaryNumber::convertFromBinaryToDecimal(new BinaryNumber("100"));
        
        $this->assertIsNumeric($result);
        $this->assertEquals(4, $result);
    }

    public function testConvertFromBinaryToDecimal_Zero(): void {
        $result = BinaryNumber::convertFromBinaryToDecimal(new BinaryNumber("0"));
        
        $this->assertIsNumeric($result);
        $this->assertEquals(0, $result);
    }

    //Testing method clearLeftZeroes

    public function testClearLeftZeroes_NumberWithLeftZeroes(): void {
        $result = BinaryNumber::clearLeftZeroes(new BinaryNumber("0010"));
        
        $this->assertIsObject($result);
        $this->assertEquals("10", $result->getValue());
    }

    public function testClearLeftZeroes_NumberWithoutLeftZeroes(): void {
        $result = BinaryNumber::clearLeftZeroes(new BinaryNumber("10"));
        
        $this->assertIsObject($result);
        $this->assertEquals("10", $result->getValue());
    }

    public function testClearLeftZeroes_Zero(): void {
        $result = BinaryNumber::clearLeftZeroes(new BinaryNumber("0"));
        
        $this->assertIsObject($result);
        $this->assertEquals("0", $result->getValue());
    }

    //Testing method add

    public function testAddition_AddTwoPositiveNumbers(): void {
        $number = new BinaryNumber("100");
        $sum = $number->add(new BinaryNumber("11"));

        $this->assertIsObject($sum);
        $this->assertEquals(7, BinaryNumber::convertFromBinaryToDecimal($sum));
        $this->assertEquals("111", $sum->getValue());

    }

    public function testAddition_AddZeroToPositiveNumber(): void {
        $number = new BinaryNumber("1000");
        $sum = $number->add(new BinaryNumber("0"));

        $this->assertIsObject($sum);
        $this->assertEquals(8, BinaryNumber::convertFromBinaryToDecimal($sum));
        $this->assertEquals("1000", $sum->getValue());

    }

    public function testAddition_AddZeroToZero(): void {
        $number = new BinaryNumber("0");
        $sum = $number->add(new BinaryNumber("0"));

        $this->assertIsObject($sum);
        $this->assertEquals(0, BinaryNumber::convertFromBinaryToDecimal($sum));
        $this->assertEquals("0", $sum->getValue());

    }

    //Testing method subtract
    
    public function testSubtraction_SubtractTwoPositiveNumbers(): void {
        $minuend = new BinaryNumber("1000");
        $subtrahend = new BinaryNumber("100");
        
        $difference = $minuend->subtract($subtrahend);

        $this->assertIsObject($difference);
        $this->assertEquals("100", $difference->getValue());
    }

    public function testSubtraction_SubtractZeroFromPositiveNumbers(): void {
        $minuend = new BinaryNumber("1000");
        $subtrahend = new BinaryNumber("0");
        
        $difference = $minuend->subtract($subtrahend);

        $this->assertIsObject($difference);
        $this->assertEquals("1000", $difference->getValue()); 
    }

    //Testing method multiply

    public function testMultiplication_MultiplyTwoPositiveNumbers(): void {
        $factor1 = new BinaryNumber("1000");
        $factor2 = new BinaryNumber("10");
        
        $product = $factor1->multiply($factor2);

        $this->assertIsObject($product);
        $this->assertEquals("10000", $product->getValue());
        $this->assertEquals(16, BinaryNumber::convertFromBinaryToDecimal($product));
    }

    public function testMulltiplication_MultiplyZeroToPositiveNumber(): void {
        $factor1 = new BinaryNumber("1000");
        $factor2 = new BinaryNumber("0");
        
        $product = $factor1->multiply($factor2);

        $this->assertIsObject($product);
        $this->assertEquals("0", $product->getValue());
        $this->assertEquals(0, BinaryNumber::convertFromBinaryToDecimal($product));
    }

    //Testing method compare

    public function testComparision_CompareTwoIdenticalNumbers(): void {
        $number1= new BinaryNumber("1000");
        $number2 = new BinaryNumber("1000");
            
        $resultNumber1 = $number1->compare($number2);
        $resultNumber2 = $number2->compare($number1);

        $this->assertIsInt($resultNumber1);
        $this->assertIsInt($resultNumber2);
        $this->assertEquals(0, $resultNumber1);
        $this->assertEquals(0, $resultNumber2);
        $this->assertEquals($resultNumber1, $resultNumber2);
    }
    
    public function testComparision_CompareTwoDifferentNumbers(): void {
        $number1= new BinaryNumber("100");
        $number2 = new BinaryNumber("1000");
    
        $resultNumber1 = $number1->compare($number2);
        $resultNumber2 = $number2->compare($number1);

        $this->assertIsInt($resultNumber1);
        $this->assertIsInt($resultNumber2);
        $this->assertEquals(-1, $resultNumber1);
        $this->assertEquals(1, $resultNumber2);
        $this->assertNotEquals($resultNumber1, $resultNumber2);
    }

    //Testing method divide

    public function testDivision_DivideTwoIdenticalPositiveNumbers(): void {
        $dividend = new BinaryNumber("1000");
        $divisor = new BinaryNumber("1000");
            
        $quotient= $dividend->divide($divisor);

        $this->assertIsObject($quotient);
        $this->assertEquals('1', $quotient->getValue());
    }

    public function testDivision_DivideTwoDifferentPositiveNumbers(): void {
        $dividend = new BinaryNumber("100000");
        $divisor = new BinaryNumber("1000");
            
        $quotient= $dividend->divide($divisor);

        $this->assertIsObject($quotient);
        $this->assertEquals('100', $quotient->getValue());
    }

    public function testDivision_ZeroDivideByPositiveNumber(): void {
        $dividend = new BinaryNumber("0");
        $divisor = new BinaryNumber("1000");
            
        $quotient= $dividend->divide($divisor);

        $this->assertIsObject($quotient);
        $this->assertEquals('0', $quotient->getValue());
    }

    public function testDivision_PositiveNumberDivideByZero(): void {
        $dividend = new BinaryNumber("1000");
        $divisor = new BinaryNumber("0");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Сan't divide by zero");

        $dividend->divide($divisor);
    }

    //Testing method isBinaryNumber

    public function testIsBinaryNumber_BinaryNumber(): void {
        $result = BinaryNumber::isBinaryNumber("101010");

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testIsBinaryNumber_NotBinaryNumber(): void {
        $result = BinaryNumber::isBinaryNumber("1002");

        $this->assertIsBool($result);
        $this->assertFalse($result);
    }
    
    //Testing method convertFromBinaryToHexadecimal

    public function testConvertFromBinaryToHexadecimal_NumberIsSplitedByFour(): void {
        $result = BinaryNumber::convertFromBinaryToHexadecimal(new BinaryNumber("11011001"));
        
        $this->assertIsString($result);
        $this->assertEquals("D9",$result);
    }

    public function testConvertFromBinaryToHexadecimal_NumberIsNotSplitedByFour(): void {
        $result = BinaryNumber::convertFromBinaryToHexadecimal(new BinaryNumber("1011001"));
        
        $this->assertIsString($result);
        $this->assertEquals("59",$result);
    }

    public function testConvertFromBinaryToHexadecimal_Zero(): void {
        $result = BinaryNumber::convertFromBinaryToHexadecimal(new BinaryNumber("0"));
        
        $this->assertIsString($result);
        $this->assertEquals("0",$result);
    }

    //Testing method convertFromHexadecimalToBinary

    public function testConvertFromHexadecimalToBinary_PositiveHexadecimalNumber(): void {
        $result = BinaryNumber::convertFromHexadecimalToBinary("AA10");

        $this->assertIsObject($result);
        $this->assertEquals("1010101000010000", $result->getValue());
    }

    public function testConvertFromHexadecimalToBinary_PositiveHexadecimalNumberWithLeftZeroInBinarySystem(): void {
        $result = BinaryNumber::convertFromHexadecimalToBinary("1A10");

        $this->assertIsObject($result);
        $this->assertEquals("1101000010000", $result->getValue());
    }

    public function testConvertFromHexadecimalToBinary_Zero(): void {
        $result = BinaryNumber::convertFromHexadecimalToBinary("0");

        $this->assertIsObject($result);
        $this->assertEquals("0", $result->getValue());
    }

    //Testing method convertFromBinaryToOctal

    public function testConvertFromBinaryToOctal_NumberIsSplitedByThree() : void {
        $result = BinaryNumber::convertFromBinaryToOctal(new BinaryNumber("101101"));
        
        $this->assertIsString($result);
        $this->assertEquals("55",$result);
    }

    public function testConvertFromBinaryToOctal_NumberIsNotSplitedByThree() : void {
        $result = BinaryNumber::convertFromBinaryToOctal(new BinaryNumber("1101101"));
        
        $this->assertIsString($result);
        $this->assertEquals("155",$result);
    }

    public function testConvertFromBinaryToOctal_Zero() : void {
        $result = BinaryNumber::convertFromBinaryToOctal(new BinaryNumber("0"));
        
        $this->assertIsString($result);
        $this->assertEquals("0", $result);
    }

    //Testing method convertFromOctalToBinary

    public function testConvertFromOctalToBinary_PositiveOctalNumber(): void {
        $result = BinaryNumber::convertFromOctalToBinary("775");

        $this->assertIsObject($result);
        $this->assertEquals("111111101", $result->getValue());
    }

    public function testConvertFromOctalToBinary_PositiveOctalNumberWithLeftZeroInBinarySystem(): void {
        $result = BinaryNumber::convertFromOctalToBinary("175");

        $this->assertIsObject($result);
        $this->assertEquals("1111101", $result->getValue());
    }

    public function testConvertFromOctalToBinary_Zero(): void {
        $result = BinaryNumber::convertFromOctalToBinary("0");

        $this->assertIsObject($result);
        $this->assertEquals("0", $result->getValue());
    }


}

?>