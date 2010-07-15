<?php
/**
 * Yahoo_Sniffs_PHP_YintlFunctionsSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy 'Bob' Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: YintlFunctionsSniff.php,v 1.2 2008/12/01 16:33:56 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Yahoo_Sniffs_PHP_YintlFunctionsSniff.
 *
 * Discourages the use of non intl aware php function calls in Yahoo! Land
 *
 * Example
 * <code>
 * //BAD:
 * $myDate   = date('Y m d, H:i:s');
 * $myUxDate = mktime($hour, $minute, $second, $month, $day, $year);
 *
 * //GOOD:
 * $myDate   = yintl_fmttime_u8('%Y %m %d, %H:%M:%S', time(), 'Europe/London');
 * $myUxDate = yintl_mktime($hour, $minute, $second, $month, $day, $year, 'America/Los_Angeles');
 * </code>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   Release: 1.0.1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Yahoo_Sniffs_PHP_YintlFunctionsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is NULL if no alternative exists. IE, the
     * function should just not be used.
     *
     * @var array(string => string|null)
     */
    protected $forbiddenFunctions = array(
                                     'mktime'              => 'yintl_mktime',
                                     'gmmktime'            => 'yintl_mktime',
                                     'localtime'           => 'yintl_localtime',
                                     'date'                => 'yintl_strftime_u8',
                                     'idate'               => 'yintl_strftime_u8',
                                     'gmdate'              => 'yintl_strftime_u8',
                                     'strftime'            => 'yintl_strftime_u8',
                                     'gmstrftime'          => 'yintl_strftime_u8',
                                     'yintl_strftime'      => 'yintl_strftime_u8',
                                     'yintl_fmttime'       => 'yintl_fmttime_u8',
                                     'yintl_get_day'       => 'yintl_get_day_u8',
                                     'yintl_getall_days'   => 'yintl_getall_days_u8',
                                     'yintl_get_month'     => 'yintl_get_month_u8',
                                     'yintl_getall_months' => 'yintl_getall_months_u8',
                                    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], array(T_DOUBLE_COLON, T_OBJECT_OPERATOR, T_FUNCTION)) === true) {
            // Not a call to a PHP function.
            return;
        }

        $function = strtolower($tokens[$stackPtr]['content']);

        if (in_array($function, array_keys($this->forbiddenFunctions)) === false) {
            return;
        }

        $error = "The use of function $function() is forbidden";
        if ($this->forbiddenFunctions[$function] !== null) {
            $error .= '; use '.$this->forbiddenFunctions[$function].'() instead';
        }

        $phpcsFile->addError($error, $stackPtr);

    }//end process()


}//end class

?>
