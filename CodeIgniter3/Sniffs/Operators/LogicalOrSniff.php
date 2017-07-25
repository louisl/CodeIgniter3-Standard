<?php
/**
 * Logical Or
 *
 * @package   CodeIgniter3-Standard
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2017 Louis Linehan
 * @license   https://github.com/louisl/CodeIgniter3-Standard/blob/master/LICENSE MIT License
 */

namespace CodeIgniter3\Sniffs\Operators;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Logical Or Sniff
 *
 * Check that the 'or' operator is the logical version 'or'.
 *
 * @author Louis Linehan <louis.linehan@gmail.com>
 */
class LogicalOrSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_BOOLEAN_OR);

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

        $error = false;

        if ($tokens[$stackPtr]['code'] === T_BOOLEAN_OR) {
            $error = '"%s" is not allowed, use "OR" instead';
        }

        if ($error !== false) {
            $data = array($tokens[$stackPtr]['content']);
            $fix  = $phpcsFile->addFixableError($error, $stackPtr, 'BooleanOrNotAllowed', $data);
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken($stackPtr, 'OR');
                $phpcsFile->fixer->endChangeset();
            }
        }

    }//end process()


}//end class
