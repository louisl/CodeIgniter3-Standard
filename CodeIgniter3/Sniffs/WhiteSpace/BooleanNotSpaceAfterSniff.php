<?php
/**
 * CodeIgniter3_Sniffs_WhiteSpace_LogicalNotSpacingSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace CodeIgniter3\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * CodeIgniter3_Sniffs_WhiteSpace_LogicalNotSpacingSniff.
 *
 * Ensures that at exactly a space precedes and follows the logical operator !.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class BooleanNotSpaceAfterSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_BOOLEAN_NOT);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The current file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $nextToken = $tokens[($stackPtr + 1)];
        if (T_WHITESPACE !== $nextToken['code']) {
            $error = 'There must be a space after !';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'BooleanNotNoWhiteSpaceAfter');

            if ($fix === true) {
                $nextContentPtr = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
                $phpcsFile->fixer->beginChangeset();
                for ($i = ($nextContentPtr + 1); $i < $stackPtr; $i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->addContent(($stackPtr), ' ');
                $phpcsFile->fixer->endChangeset();

                $phpcsFile->recordMetric($stackPtr, 'Boolean not whitespace', 'no space after');
            }//end if
        }//end if

    }//end process()


}//end class
