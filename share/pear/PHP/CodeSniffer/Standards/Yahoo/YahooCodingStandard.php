<?php
/**
 * Yahoo Coding Standard.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: YahooCodingStandard.php,v 1.6 2009/01/26 12:37:33 abrock Exp $
 */

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

/**
 * Yahoo Coding Standard.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 */
class PHP_CodeSniffer_Standards_Yahoo_YahooCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
{


    /**
     * Return a list of external sniffs to include with this standard.
     *
     * The MySource standard is an extension of the Squiz standard, with
     * specific tests for the MySource CMS, so include the whole Squiz standard.
     *
     * @return array
     */
    public function getIncludedSniffs()
    {
        return array(
                'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',
                //'PEAR/Sniffs/Files/IncludingFileSniff.php',
                'PEAR/Sniffs/Files/LineEndingsSniff.php',
                //'Squiz/Sniffs/Operators/ValidLogicalOperatorsSniff.php',
                'Squiz/Sniffs/PHP/EvalSniff.php',
               );

    }//end getIncludedSniffs()

    /**
     * Return a list of external sniffs to exclude from this standard.
     *
     * The PHP_CodeSniffer standard combines the PEAR and Squiz standards
     * but removes some sniffs from the Squiz standard that clash with
     * those in the PEAR standard.
     *
     * @return array
     */
    public function getExcludedSniffs()
    {
        return array(
                    );

    }//end getExcludedSniffs()

}//end class
?>
