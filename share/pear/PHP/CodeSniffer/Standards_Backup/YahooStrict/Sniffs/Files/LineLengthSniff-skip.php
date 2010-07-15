<?php
/**
 * Yahoo_Sniffs_Files_LineLengthSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: LineLengthSniff.php,v 1.2 2008/10/09 14:18:24 abrock Exp $
 */

/**
 * Yahoo_Sniffs_Files_LineLengthSniff.
 *
 * Checks all lines in the file, and throws warnings if they are over 78
 * characters in length and errors if they are over 110.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 */
class YahooStrict_Sniffs_Files_LineLengthSniff extends Generic_Sniffs_Files_LineLengthSniff
{

    /**
     * The limit that the length of a line should not exceed.
     *
     * @var int
     */
    protected $lineLimit = 78;

    /**
     * The limit that the length of a line must not exceed.
     *
     * Set to zero (0) to disable.
     *
     * @var int
     */
    protected $absoluteLineLimit = 110;


}//end class

?>
