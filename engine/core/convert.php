<?php

/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * Core API for data conversion
 *
 * File version: 1.6
 * Last update: 01/26/2017 
 */

/**
 * Various ZnetDK conversion methods
 */
class Convert {
    
    /**
     * Formats the specified number to be displayed as a money
     * @param mixed $number Number to convert
     * @param boolean $withCurrencySymbol Specifies whether the currency symbol
     * is to be added to the formatted number (added by default)
     * @param integer $numberOfDecimals Number of decimals of the converted 
     * @param boolean $forPdfPrinting If TRUE, the currency symbol of the 
     * formated number is suitable for PDF printing
     * amount
     * @return string Number formated as a money 
     */
    static public function toMoney($number, $withCurrencySymbol=TRUE, $numberOfDecimals=NULL, $forPdfPrinting=FALSE) {
        if (is_null($number)) {
            return NULL;
        }
        $decimalNumber = self::toDecimal($number);
        $decimalSeparator = \api\Locale::getDecimalSeparator();
        $thousandsSeparator = \api\Locale::getThousandsSeparator();
        $decimals = is_null($numberOfDecimals) ? \api\Locale::getNumberOfDecimals() : $numberOfDecimals;
        $numberWithoutSymbol = \number_format($decimalNumber,$decimals,$decimalSeparator,$thousandsSeparator);
        return $withCurrencySymbol
            ?\api\Locale::addCurrencySymbol($numberWithoutSymbol, $forPdfPrinting)
            : $numberWithoutSymbol;
    }

    /**
     * Converts the string number to a formated number suitable for storage in
     * the database.
     * The decimal separator is a dot (i.e '.') and the grouping character is
     * removed. 
     * @param string $stringNumber The number in string format
     * @param integer $numberOfDecimals Number of decimals expected in the 
     * database
     * @return string The formated amount
     */
    static public function toDecimalForDB($stringNumber, $numberOfDecimals=NULL) {
        if (trim($stringNumber) === '') {
            return '';
        }
        $convertedAmount = self::toMoney($stringNumber, FALSE, $numberOfDecimals);
        return str_replace(array(',',' '), array('.',''), $convertedAmount);
    }
    
    /**
     * Converts a decimal number, especially from a string format to a float
     * format.
     * @param mixed $value Decimal number to convert.
     * @return float Value converted to a float number
     */
    static public function toDecimal($value) {
        if (!is_string($value)) {
            return $value;
        }
        $decimalSeparator = \api\Locale::getDecimalSeparator();
        $thousandsSeparator = \api\Locale::getThousandsSeparator();
        $separatorOK = str_replace($decimalSeparator, '.',strval($value));
        $thousandsAndSeparatorOK = str_replace($thousandsSeparator,'',$separatorOK);
        return floatval($thousandsAndSeparatorOK);
    }
    
    /**
     * Converts the specified string in UTF-8 character set
     * @param String $string String to convert
     * @return String Converted string in UTF-8
     */
    static public function toUTF8($string) {
        json_encode($string);
        if (json_last_error() === JSON_ERROR_UTF8) {
            return utf8_encode($string);
        } else {
            return $string;
        }
    }
    
    /**
     * Formats the specified W3C date using the locale settings of the application
     * If the date is followed by a time, this time is kept as is.
     * @param string $W3CDate Date formatted in W3C format ('Y-m-d')
     * @return string Date formatted according to the current locale settings
     */
    static public function W3CtoLocaleDate($W3CDate) {
        if (empty($W3CDate)) {
            return NULL;
        }
        $dateToConvert = new \DateTime($W3CDate);
        $formatedDate = strftime(\api\Locale::getLocaleDateFormat(), $dateToConvert->getTimestamp());
        return strlen($W3CDate) === 19 ? $formatedDate . substr($W3CDate, 10) : $formatedDate;
    }

    /**
     * Converts a 'DateTime' object to a localized date
     * @param DateTime $dateTime A DateTime object
     * @return string The localized date
     */
    static public function toLocaleDate($dateTime) {
        return strftime(\api\Locale::getLocaleDateFormat(), $dateTime->getTimestamp());
    }
    
    /**
     * Converts a '\DateTime' object to a W3C formated date
     * @param DateTime $dateTime A DateTime object
     * @return string Formated date in W3C standard
     */
    static public function toW3CDate($dateTime) {
        return $dateTime->format('Y-m-d');
    }
    
    /**
     * Converts the values of the specified array to the ISO-8859-1 charset
     * @param array $values Values in a one dimension array
     * @return array Values converted to ISO-8859-1 charset
     */
    static public function valuesToAnsi($values) {
        $convertedValues = array();
        foreach ($values as $key => $value) {
            $convertedValues[$key] = utf8_decode($value);
        }
        return $convertedValues;
    }
    
}