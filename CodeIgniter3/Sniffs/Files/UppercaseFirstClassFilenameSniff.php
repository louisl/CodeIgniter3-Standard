<?php
/**
 * Uppercase First Class Filename
 *
 * @package   CodeIgniter3-Standard
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2017 Louis Linehan
 * @license   https://github.com/louisl/CodeIgniter3-Standard/blob/master/LICENSE MIT License
 */

namespace CodeIgniter3\Sniffs\Files;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Uppercase First Class Filename Sniff
 *
 * Checks that all class files names have an uppercase first character
 * and that they match the guideline if user defined.
 *
 * @author Louis Linehan <louis.linehan@gmail.com>
 */
class UppercaseFirstClassFilenameSniff implements Sniff
{

    /**
     * If the file has a bad filename.
     *
     * Change to true and check it later to avoid displaying multiple errors.
     *
     * @var boolean
     */
    protected $badFilename = false;


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
                T_TRAIT,
               );

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     *
     * @return int
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $prefix         = '';
        $fileName       = basename($phpcsFile->getFilename());
        $className      = trim($phpcsFile->getDeclarationName($stackPtr));
        $nextContentPtr = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        $parts          = explode('_', $className);

        if (count($parts) > 1) {
            // Is the first part all upper case.
            if (ctype_upper($parts[0]) === true) {
                // It's likely CI_ or user defined like MY_.
                $prefix = $parts[0];
                unset($parts[0]);
                $newClassName     = implode('_', $parts);
                $expectedFilename = strtoupper($prefix).'_'.ucfirst(strtolower($newClassName)).'.php';
            } else {
                $expectedFilename = ucfirst(strtolower($className)).'.php';
            }
        } else {
            $expectedFilename = ucfirst(strtolower($className)).'.php';
        }

        if ($fileName !== $expectedFilename && $this->badFilename === false) {
            $data  = array(
                      $fileName,
                      $expectedFilename,
                     );
            $error = 'Filename "%s" doesn\'t match the expected filename "%s"';
            $phpcsFile->addError($error, $nextContentPtr, 'NotFound', $data);
            $phpcsFile->recordMetric($nextContentPtr, 'Uppercase first character filename', 'no');
            $this->badFilename = true;
        } else {
            $phpcsFile->recordMetric($nextContentPtr, 'Uppercase first character filename', 'yes');
        }

        // Ignore the rest of the file.
        return ($phpcsFile->numTokens + 1);

    }//end process()


}//end class
