<?php
/**
 * Yahoo_Sniffs_Comments_VersionCvsIdTagSniff
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Dafydd James <dafydd@yahoo-inc.com>
 * @version   CVS: $Id: VersionCvsIdTagSniff.php,v 1.2 2008/12/17 15:33:56 abrock Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Yahoo_Sniffs_Comments_VersionCvsIdTagSniff.
 *
 * Checks that the PHPDoc-style comment at the start of the file contains a version tag,
 * and that the contents of the tag conforms to the CVS "Id" tag format.
 *
 * <code>
 *  * @version CVS: $Id: VersionCvsIdTagSniff.php,v 1.2 2008/12/17 15:33:56 abrock Exp $
 * </code>
 *
 * @category  Comments
 * @package   PHP_CodeSniffer
 * @author    Dafydd James <dafydd@yahoo-inc.com>
 */
class Yahoo_Sniffs_Comments_VersionCvsIdTagSniff implements PHP_CodeSniffer_Sniff
{
    const VERSION_FORMAT = '@version CVS: $Id: VersionCvsIdTagSniff.php,v 1.2 2008/12/17 15:33:56 abrock Exp $';

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // We are only interested if this is the first open tag.
        if ($stackPtr !== 0) {
            if ($phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1)) !== false) {
                return;
            }
        }

        $tokens = $phpcsFile->getTokens();

        // Find the next T_DOC_COMMENT, assume it's the file-level comment (separate sniff to check for the validity of this assumption)
        $commentStart = $phpcsFile->findNext(T_DOC_COMMENT, ($stackPtr + 1), null);

        if (false === $commentStart)
        {
            $this->addError($commentStart, $phpcsFile);
            return;
        }

        // Extract the header comment docblock.
        $commentEnd = ($phpcsFile->findNext(T_DOC_COMMENT, ($commentStart + 1), null, true) - 1);

        $comment = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

        // Parse the header comment docblock.
        try {
            $this->commentParser = new PHP_CodeSniffer_CommentParser_ClassCommentParser($comment, $phpcsFile);
            $this->commentParser->parse();
        } catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
            $line = ($e->getLineWithinComment() + $commentStart);
            $phpcsFile->addError($e->getMessage(), $line);
            return;
        }

        /** @var PHP_CodeSniffer_CommentParser_SingleElement */
        $version = $this->commentParser->getVersion();

        if(true === is_null($version))
        {
            $this->addError($commentStart, $phpcsFile);
            return;
        }

        $versionString = $version->getContent();
        $pattern = '/CVS\: \$Id\: .*\$/';
        if(0 === preg_match($pattern, $versionString))
        {
            $error = '@version tag in file-level comment doesn\'t contain CVS Id tag! The format should be: ' . self::VERSION_FORMAT;
            $phpcsFile->addError($error, $commentStart);
            return;
        }

    }//end process()

    /**
     * Helper function, to prevent duplicate code.
     *
     * Logs an error at a particular line in the code.
     *
     * @param int                  $line      Linenumber at which error was detected
     * @param PHP_CodeSniffer_File $phpcsFile File in which error was detected
     * @param string               $format    Format to use for the CVS ID line
     *
     * @return void
     */
    private function addError($line, PHP_CodeSniffer_File $phpcsFile, $format = self::VERSION_FORMAT)
    {
        $error = "You need to add a '@version' tag to your file-level comment, following the format: '$format', so that CVS can stamp a version on the file.";
        $phpcsFile->addError($error, $line);
    }
}