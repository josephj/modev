<?php
/**
 * Yahoo_Sniffs_PHP_LintCheckSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: LintCheckSniff.php,v 1.1 2008/08/05 10:30:47 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Yahoo_Sniffs_PHP_LintCheckSniff.
 *
 * Performs a lint check on each php file and reports errors
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Andy Brockhurst <abrock@yahoo-inc.com>
 * @version   Release: 1.0.1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Yahoo_Sniffs_PHP_LintCheckSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_TAG);

    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // Because we are analyzing the whole file in one step, execute this method
        // only on first occurence of a T_OPEN_TAG.
        $prevOpenTag = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($prevOpenTag !== false) {
            return;
        }

        $fileName = $phpcsFile->getFilename();

        $cmd = 'php -l '.$fileName.' 2>&1';

        $exitCode = exec($cmd, $output, $retval);

        if (true === is_array($output)) {
            $msg = join("\n", $output);
        }

        /* handle not being able to exec the lint check */
        if (true === is_numeric($exitCode) && 0 < $exitCode) {
            throw new PHP_CodeSniffer_Exception("Failed invoking php -l, exitcode was [$exitCode], retval was [$retval], output was [$msg]");
        }

        if ( 0 < $retval )
        {
            $error = 'Failed PHP lint check';
            if ( 0 < PHP_CODESNIFFER_VERBOSITY )
            {
                $error .= ":\n".$msg;
            }
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()

}//end class