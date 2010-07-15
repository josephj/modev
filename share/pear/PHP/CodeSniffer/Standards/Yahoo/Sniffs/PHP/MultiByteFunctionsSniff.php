<?php
/**
 * Yahoo_Sniffs_PHP_MultiByteFunctionsSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy 'Bob' Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: MultiByteFunctionsSniff.php,v 1.4 2008/12/02 11:00:20 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Yahoo_Sniffs_PHP_MultiByteFunctionsSniff.
 *
 * Discourages the use of non multibyte aware string functions
 *
 * <code>
 * //BAD:
 * $lenOrBytesIDontKnowNow = strlen($_POST['postVariableIHaveNoIdeaOfEncodingFor']);
 * $borkedContent = substr($ThisContainsUTF8Text, 0, -4);
 *
 * //GOOD:
 * $length      = mb_strlen($_POST['postVariableIHaveNoIdeaOfEncodingFor']);
 * $realContent = mb_substr($ThisContainsUTF8Text, 0, -4);
 * </code>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   Release: 1.0.1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Yahoo_Sniffs_PHP_MultiByteFunctionsSniff implements PHP_CodeSniffer_Sniff
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
                                     'strlen'       => 'mb_strlen',
                                     'strpos'       => 'mb_strpos',
                                     'stripos'      => 'mb_stripos',
                                     'strstr'       => 'mb_strstr',
                                     'stristr'      => 'mb_stristr',
                                     'strrchr'      => 'mb_strrchr',
                                     'strrichr'     => 'mb_strrichr',
                                     'strrpos'      => 'mb_strrpos',
                                     'strripos'     => 'mb_strripos',
                                     'strtolower'   => 'mb_strtolower',
                                     'strtoupper'   => 'mb_strtoupper',
                                     'substr'       => 'mb_substr',
                                     'substr_count' => 'mb_substr_count',
                                     'ucfirst'      => 'mb_convert_case',
                                     'ucwords'      => 'mb_convert_case',
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
