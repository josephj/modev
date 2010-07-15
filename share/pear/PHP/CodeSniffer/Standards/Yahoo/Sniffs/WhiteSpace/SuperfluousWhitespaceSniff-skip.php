<?php
/**
 * Yahoo_Sniffs_WhiteSpace_SuperfluousWhitespaceSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: SuperfluousWhitespaceSniff.php,v 1.1 2008/06/06 10:03:07 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Yahoo_Sniffs_WhiteSpace_SuperfluousWhitespaceSniff.
 *
 * Checks that no whitespace proceeds the first content of the file, exists
 * after the last content of the file only.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Yahoo_Sniffs_WhiteSpace_SuperfluousWhitespaceSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_OPEN_TAG,
                T_CLOSE_TAG,
                T_WHITESPACE,
                T_COMMENT,
               );

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
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

        if ($tokens[$stackPtr]['code'] === T_OPEN_TAG) {

            /*
                Check for start of file whitespace.
            */

            // If its the first token, then there is no space.
            if ($stackPtr === 0) {
                return;
            }

            for ($i = ($stackPtr - 1); $i >= 0; $i--) {
                // If we find something that isn't inline html then there is something previous in the file.
                if ($tokens[$i]['type'] !== 'T_INLINE_HTML') {
                    return;
                }

                // If we have ended up with inline html make sure it isn't just whitespace.
                $tokenContent = trim($tokens[$i]['content']);
                if ($tokenContent !== '') {
                    return;
                }
            }

            $phpcsFile->addError('Additional whitespace found at start of file', $stackPtr);

        } else if ($tokens[$stackPtr]['code'] === T_CLOSE_TAG) {

            /*
                Check for end of file whitespace.
            */
            $tokenContent = $tokens[$stackPtr]['content'];
            if (isset($tokens[($stackPtr + 1)]) === false && $tokenContent === trim($tokenContent)) {
                // The close PHP token is the last in the file.
                return;
            }

            for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
                // If we find something that isn't inline html then there
                // is more to the file.
                if ($tokens[$i]['type'] !== 'T_INLINE_HTML') {
                    return;
                }

                // If we have ended up with inline html make sure it
                // isn't just whitespace.
                $tokenContent = trim($tokens[$i]['content']);
                if (empty($tokenContent) === false) {
                    return;
                }
            }

            $phpcsFile->addError('Additional whitespace found at end of file', $stackPtr);

        }
    }//end process()


}//end class

?>
