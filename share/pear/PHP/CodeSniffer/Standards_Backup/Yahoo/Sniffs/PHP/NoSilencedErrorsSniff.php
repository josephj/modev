<?php
/**
 * Yahoo_Sniffs_PHP_NoSilencedErrorsSniff
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: NoSilencedErrorsSniff.php,v 1.4 2009/01/26 12:29:59 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Yahoo_Sniffs_PHP_NoSilencedErrorsSniff.
 *
 * Throws an error when any code prefixed with an asperand is encountered.
 *
 * <code>
 *  if (@in_array($array, $needle))
 *  {
 *      doSomething();
 *  }
 * </code>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   Release: 1.0.1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Yahoo_Sniffs_PHP_NoSilencedErrorsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_ASPERAND);
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
        $warning = "Silencing errors is not allowed";

        $phpcsFile->addError($warning, $stackPtr);

    }//end process()

}