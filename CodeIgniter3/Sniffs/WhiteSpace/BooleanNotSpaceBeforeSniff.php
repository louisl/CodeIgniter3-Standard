<?php
/**
 * CodeIgniter3\Sniffs\WhiteSpace\BooleanNotSpaceBeforeSniff
 *
 * @package   PHP_CodeSniffer
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2017 Louis Linehan
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace CodeIgniter3\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Boolean not space before sniff
 *
 * There must be a space before boolean not "!".
 *
 * @category  PHP
 * @package   CodeIniter\Sniffs\WhiteSpace
 * @author    Louis Linehan <louis.linehan@gma-remove-il.com>
 * @copyright 2017 Louis Linehan
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class BooleanNotSpaceBeforeSniff implements Sniff
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

        // Find previous non whitespace token.
        $prevNonWhitespacePtr = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        $token = $tokens[$prevNonWhitespacePtr];

        $prevToken = $tokens[($stackPtr - 1)];

        if (T_WHITESPACE !== $prevToken['code']) {
            $error = 'There must be a space before !';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'BooleanNotNoWhiteSpaceBefore');

            if ($fix === true) {
                $prevContentPtr = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                $phpcsFile->fixer->beginChangeset();
                for ($i = ($prevContentPtr + 1); $i < $stackPtr; $i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->addContentBefore(($stackPtr), ' ');
                $phpcsFile->fixer->endChangeset();
                $phpcsFile->recordMetric($stackPtr, 'Boolean not whitespace', 'no space before');
            }//end if
        }//end if

    }//end process()


}//end class
