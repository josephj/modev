<?php
/**
 * Yahoo_Sniffs_Files_ClosingTagWarnSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: ClosingTagWarnSniff.php,v 1.1 2008/06/06 10:03:06 abrock Exp $
 */

/**
 * Yahoo_Sniffs_Files_LineEndingsSniff.
 *
 * Checks that the file does not end with a closing tag, warning only.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 */
class Yahoo_Sniffs_Files_ClosingTagWarnSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLOSE_TAG);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if ($next === false) {
            $warning = 'A closing tag is not necessary at the end of a PHP file';
            $phpcsFile->addWarning($warning, $stackPtr);
        }

    }//end process()


}//end class

?>