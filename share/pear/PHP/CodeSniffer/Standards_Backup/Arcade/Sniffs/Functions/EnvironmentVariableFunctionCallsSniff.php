<?php
/**
 * Arcade_Sniffs_Functions_EnvironmentVariableFunctionCalls.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Bob T Builder <abrock@yahoo-inc.com>
 * @version   CVS: $Id: EnvironmentVariableFunctionCallsSniff.php,v 1.1 2008/10/02 16:26:30 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Arcade_Sniffs_Functions_EnvironmentVariableFunctionCalls.
 *
 * Checks that calls environment variable functions use arcade calls instead of getenv, yiv or $_SERVER
 *
 * Example:
 *
 * <code>
 *
 * //BAD:
 * $address = $_SERVER['REMOTE_ADDR'];
 * $address = $_SERVER["REMOTE_ADDR"];
 * $address = getenv('REMOTE_ADDR');
 * $address = getenv("REMOTE_ADDR");
 * $address = yahoo_get_data(YIV_SERVER, 'REMOTE_ADDR', YIV_FILTER_HOSTPORT);
 * $address = yahoo_get_data(YIV_SERVER, "REMOTE_ADDR", YIV_FILTER_HOSTPORT);
 *
 * //GOOD:
 * $address = ArcadeFactory::$Arcade->getServerParameter("REMOTE_ADDR");
 * $address = ArcadeFactory::$Arcade->getServerParameter('REMOTE_ADDR');
 * </code>
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Bob T Builder <abrock@yahoo-inc.com>
 */
class Arcade_Sniffs_Functions_EnvironmentVariableFunctionCallsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Array of environment variables to find and check for correct usage
     *
     * @var string[]
     */
    protected $environmentVariables = array(
                                            'REMOTE_ADDR',
                                            'HTTP_HOST',
                                           );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CONSTANT_ENCAPSED_STRING);
    }


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

        if (false === in_array(trim($tokens[$stackPtr]['content'], '"\''), $this->environmentVariables)) {
            return;
        }


        $prevToken = $phpcsFile->findPrevious(array(T_VARIABLE, T_STRING), ($stackPtr - 1));

        if (false === in_array($tokens[$prevToken]['content'], array('$_SERVER', 'getenv', 'YIV_SERVER')))
        {
            return;
        }

        $function = $tokens[$prevToken]['content'];


        $error = "The use of '$function' to retrieve server variables is not allowed in Arcade, use ArcadeFactory::\$Arcade->getServerParameter({$tokens[$stackPtr]['content']}) instead";

        $phpcsFile->addError($error, $stackPtr);

    }
}