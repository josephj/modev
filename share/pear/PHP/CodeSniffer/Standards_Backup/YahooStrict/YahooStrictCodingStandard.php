<?php
/**
 * Yahoo Strict Coding Standard.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_Yahoo
 * @author    Bob Brockhurst <abrock@yahoo-inc.com>
 * @version   CVS: $Id: YahooStrictCodingStandard.php,v 1.2 2008/10/09 13:58:00 abrock Exp $
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
class PHP_CodeSniffer_Standards_YahooStrict_YahooStrictCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
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
                'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',
                //'Generic/Sniffs/Formatting/MultipleStatementAlignmentSniff.php',
                'Generic/Sniffs/Formatting/NoSpaceAfterCastSniff.php',
                'Generic/Sniffs/Functions/OpeningFunctionBraceBsdAllmanSniff.php',
                'Generic/Sniffs/NamingConventions/UpperCaseConstantNameSniff.php',
                'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',
                'Generic/Sniffs/PHP/ForbiddenFunctionsSniff.php',
                'Generic/Sniffs/PHP/LowerCaseConstantSniff.php',
                'Generic/Sniffs/WhiteSpace/DisallowTabIndentSniff.php',
                'Generic/Sniffs/WhiteSpace/ScopeIndentSniff.php',
                'PEAR/Sniffs/Classes/ClassDeclarationSniff.php',
                'PEAR/Sniffs/Commenting/FunctionCommentSniff.php',
                'PEAR/Sniffs/Commenting/InlineCommentSniff.php',
                //'PEAR/Sniffs/Files/IncludingFileSniff.php',
                'PEAR/Sniffs/Files/LineEndingsSniff.php',
                'PEAR/Sniffs/Functions/FunctionCallArgumentSpacingSniff.php',
                'PEAR/Sniffs/Functions/FunctionCallSignatureSniff.php',
                'PEAR/Sniffs/Functions/ValidDefaultValueSniff.php',
                'PEAR/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',
                'Squiz/Sniffs/Arrays/',
                'Squiz/Sniffs/Classes/LowercaseClassKeywordsSniff.php',
                'Squiz/Sniffs/Classes/SelfMemberReferenceSniff.php',
                'Squiz/Sniffs/Commenting/DocCommentAlignmentSniff.php',
                'Squiz/Sniffs/Commenting/EmptyCatchCommentSniff.php',
                'Squiz/Sniffs/Commenting/FunctionCommentThrowTagSniff.php',
                //'Squiz/Sniffs/ControlStructures/InlineControlStructureSniff.php',
                'Squiz/Sniffs/ControlStructures/LowercaseDeclarationSniff.php',
                'Squiz/Sniffs/Functions/LowercaseFunctionKeywordsSniff.php',
                'Squiz/Sniffs/Objects/',
                'Squiz/Sniffs/Operators/',
                'Squiz/Sniffs/PHP/',
                'Squiz/Sniffs/Scope/',
                'Squiz/Sniffs/Strings/',
                'Squiz/Sniffs/WhiteSpace/LanguageConstructSpacingSniff.php',
                'Squiz/Sniffs/WhiteSpace/ObjectOperatorSpacingSniff.php',
                'Squiz/Sniffs/WhiteSpace/ScopeKeywordSpacingSniff.php',
                'Squiz/Sniffs/WhiteSpace/SemicolonSpacingSniff.php',
                'Squiz/Sniffs/WhiteSpace/SuperfluousWhitespaceSniff.php',
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
                'Squiz/Sniffs/PHP/HeredocSniff.php',
        );

    }//end getExcludedSniffs()

}//end class
?>
