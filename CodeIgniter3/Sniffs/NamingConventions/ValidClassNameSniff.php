<?php
/**
 * Valid Method Name
 *
 * @package   CodeIgniter3-Standard
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2017 Louis Linehan
 * @license   https://github.com/louisl/CodeIgniter3-Standard/blob/master/LICENSE MIT License
 */

namespace CodeIgniter3\Sniffs\NamingConventions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Valid Method Name Sniff
 *
 * Ensures that class and interface names have their first letter uppercase
 * and that words are separated with an underscore, and not CamelCased.
 *
 * @author Louis Linehan <louis.linehan@gmail.com>
 */
class ValidClassNameSniff implements Sniff
{
    /**
     * Allowed prefix characters.
     *
     * Allow common CodeIgniter first parts.
     *
     * @var array
     */
    public $allowedFirstParts = array(
                                 'CI',
                                 'MY',
                                );

    /**
     * Allowed function parts.
     *
     * Allow some common CodeIgniter functions.
     *
     * @var array
     */
    public $allowedClassParts = array('Controller');


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CLASS,
                T_INTERFACE,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The current file being processed.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Get the class name.
        $className = trim($phpcsFile->getDeclarationName($stackPtr));
        $parts     = explode('_', $className);

        if (count($parts) > 1) {
            // Is the first part all upper case.
            if (ctype_upper($parts[0]) === true) {
                // It's likely CI_ or user defined like MY_.
                $prefix = $parts[0];
                unset($parts[0]);
                $newClassName      = implode('_', $parts);
                $expectedClassName = strtoupper($prefix).'_'.ucfirst(strtolower($newClassName));
            } else {
                $expectedClassName = ucfirst(strtolower($className));
            }
        } else {
            $expectedClassName = ucfirst(strtolower($className));
        }

        // Ensures that the current class name and the expected class name match.
        if ($className !== $expectedClassName) {
            $data = array(
                     $expectedClassName,
                     $className,
                    );

            $error = 'Unexpected class name consider "%s" instead of "%s".';
            $phpcsFile->addError($error, $stackPtr, 'InvalidClassName', $data);
        }

    }//end process()


}//end class
