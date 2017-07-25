<?php
/**
 * Valid Function Name
 *
 * @package   CodeIgniter3-Standard
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2017 Louis Linehan
 * @license   https://github.com/louisl/CodeIgniter3-Standard/blob/master/LICENSE MIT License
 */

namespace CodeIgniter3\Sniffs\NamingConventions;

use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;
use CodeIgniter3\Util\Common;
use PHP_CodeSniffer\Files\File;

/**
 * Valid Function Name Sniff
 *
 * @author Louis Linehan <louis.linehan@gmail.com>
 */
class ValidFunctionNameSniff extends AbstractScopeSniff
{


    /**
     * Defines which token(s) in which scope(s) will be proceed.
     */
    public function __construct()
    {
        parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION), true);

    }//end __construct()


    /**
     * Processes the tokens outside the scope.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being processed.
     * @param int                         $stackPtr  The position where this token was
     *                                               found.
     *
     * @return void
     */
    protected function processTokenOutsideScope(File $phpcsFile, $stackPtr)
    {
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if ($functionName === null) {
            return;
        }

        // Does this function claim to be magical?
        if (preg_match('|^__[^_]|', $functionName) !== 0) {
            $errorData = array($functionName);

            $error = 'Function name "%s" is invalid; only PHP magic methods should be prefixed with a double underscore';
            $phpcsFile->addError($error, $stackPtr, 'DoubleUnderscore', $errorData);
            return;
        }

        if (Common::isLowerSnakeCase($functionName) === false
            || $functionName !== strtolower($functionName)
        ) {
            $errorData = array($functionName);

            $error = 'Function "%s" must be snake_case';
            $phpcsFile->addError($error, $stackPtr, 'FunctionNotSnakeCase', $errorData);
            return;
        }

        $warningLimit = 50;
        if (strlen($functionName) > $warningLimit) {
            $errorData = array(
                          $functionName,
                          $warningLimit,
                         );

            $warning = 'Function "%s" is over "%s" chars';
            $phpcsFile->addWarning($warning, $stackPtr, 'FunctionNameIsLong', $errorData);
        }

    }//end processTokenOutsideScope()


    /**
     * Processes the tokens within the scope.
     *
     * @param File $phpcsFile The file being processed.
     * @param int  $stackPtr  The position where this token was
     *                        found.
     * @param int  $currScope The position of the current scope.
     *
     * @return void
     */
    protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope)
    {

    }//end processTokenWithinScope()


}//end class
