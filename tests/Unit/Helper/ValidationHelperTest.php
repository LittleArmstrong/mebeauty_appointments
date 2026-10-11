<?php

namespace Tests\Unit\Helper;

use Tests\TestCase;

class ValidationHelperTest extends TestCase {
    public function testValidateDateTimeReturnsTrueOnValidValue()
    {
        $this->assertTrue(validate_datetime(date('Y-m-d H:i:s')));
    }

    public function testValidateDateTimeReturnsFalseOnInvalidValue()
    {
        $this->assertFalse(validate_datetime('invalid'));
    }

    public function testValidateNonNegativeIntegerAcceptsWholeNumbers()
    {
        foreach ([0, 3, '0', '12'] as $value) {
            $this->assertTrue(validate_non_negative_integer($value), var_export($value, true));
        }
    }

    public function testValidateNonNegativeIntegerRejectsOtherValues()
    {
        foreach ([-1, '-1', 1.5, '1.5', 'abc', '', ' 1', null, true, ['1']] as $value) {
            $this->assertFalse(validate_non_negative_integer($value), var_export($value, true));
        }
    }
}
