<?php
/**
 * Yahoo Strict Coding Standard.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: ArcadeCodingStandard.php,v 1.1 2008/08/11 17:24:52 abrock Exp $
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
class PHP_CodeSniffer_Standards_Arcade_ArcadeCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
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
                'Yahoo',
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
                     'PEAR/Sniffs/Files/IncludingFileSniff.php',
                     'PEAR/Sniffs/Files/LineEndingsSniff.php',
                     'Squiz/Sniffs/Operators/ValidLogicalOperatorsSniff.php',
                     'Squiz/Sniffs/PHP/EvalSniff.php',
                    );

    }//end getExcludedSniffs()

}//end class
?>