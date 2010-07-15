<?php
/**
 * Yahoo_Sniffs_Classes_ValidClassNameSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: ValidClassNameSniff.php,v 1.2 2008/10/09 14:18:21 abrock Exp $
 */

/**
 * Yahoo_Sniffs_Classes_ValidClassNameSniff.
 *
 * Ensures classes are in camel caps, the first letter is lowercase and consecutive caps are allowed
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 */
class YahooStrict_Sniffs_Classes_ValidClassNameSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CLASS,
                T_INTERFACE,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being processed.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Determine the name of the class or interface. Note that we cannot
        // simply look for the first T_STRING because a class name
        // starting with the number will be multiple tokens.
        $opener    = $tokens[$stackPtr]['scope_opener'];
        $nameStart = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), $opener, true);
        $nameEnd   = $phpcsFile->findNext(T_WHITESPACE, $nameStart, $opener);
        $name      = trim($phpcsFile->getTokensAsString($nameStart, ($nameEnd - $nameStart)));

        // Check for camel caps format.
        $valid = PHP_CodeSniffer::isCamelCaps($name, false, true, false);
        if ($valid === false) {
            $type  = ucfirst($tokens[$stackPtr]['content']);
            $error = "$type name \"$name\" is not in camel caps format";
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()


}//end class


?>
