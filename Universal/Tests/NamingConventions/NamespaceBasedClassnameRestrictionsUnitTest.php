<?php
/**
 * PHPCSExtra, a collection of sniffs and standards for use with PHP_CodeSniffer.
 *
 * @package   PHPCSExtra
 * @copyright 2021 PHPCSExtra Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSExtra
 */

namespace PHPCSExtra\Universal\Tests\NamingConventions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ClassnameRestrictions sniff.
 *
 * @covers PHPCSExtra\Universal\Sniffs\NamingConventions\NamespaceBasedClassnameRestrictionsSniff
 *
 * @since 1.0.0
 */
class NamespaceBasedClassnameRestrictionsUnitTest extends AbstractSniffUnitTest
{

/*
NOTES TO SELF:

Property used in the tests will need several rules and a fall-back rule.

For each rule/pattern, we need tests to:
- match pattern for file path, but not for class (error)
- match pattern for file path and for class (valid)
- not match pattern for file path (ignore)
- match file pattern for multiple patterns -> to test that only the first rule is applied (rule order)

Additionally, there should be tests which:
* Have multiple classes within a file.
* Have anonymous classes

And... we should have one or two tests with invalid regexes and figure out what we should do with those.
    And when I say invalid, I mean, the regex will never work/match.

=> have a test where the file path regex matches before the "root" dir of the files being checked

Open questions:
* Should the rule(s) also apply to traits and interfaces ?
* Should the sniff also (optionally) check class to file name translations ?
    I'd suggest to leave that to another sniff.
* Should the sniff check the FQN against the rule or just the plain classname ?
    I'd suggest leaving namespace name (vs path) checking to another sniff.


*/


/*
 * Matt's case:
 *
 * - It's not the concern of this sniff to ensure that the class name matches the file (we have other things in place
 *   in our codebase to catch this sort of thing)
 * - It should only match class names within a specified namespace (the namespace isn't part of the regex but should be
 *   configurable)
 * - I have some old classes that aren't correct and they *could* be adjusted, but it would be nice to have a baseline
 *   to ignore these existing issues (is this possible?)
 *
 * Other points:
 * - Similar to the first point above, if it *also* checked that the class name matched the file name, then it wouldn't
 *   support multiple classes in a single file
 * - Should any anonymous classes in that namespace pass?
 * - Invalid regex should fail
 *
 *
 *
 * https://regexr.com/68ik1
 *
 * Configurable options:
 * - /Version\d{14}\w{4,}/g
 * - For the `App\Migrations` namespace
 *
 * Would produce the following results:
 *
 * namespace App\Migrations;
 *
 * class Version20200101090000ItDoesThings {} // pass
 * class Versions20200101090000ItDoesThings {} // fail
 * class Version20200101090000I {} // fail
 * class Version20200101090000 {} // fail
 *
 * namespace App\OtherThings;
 * class Version20200101090000 {} // pass
 */



    /**
     * Get a list of all test files to check.
     *
     * @param string $testFileBase The base path that the unit tests files will have.
     *
     * @return string[]
     */
    protected function getTestFiles($testFileBase)
    {
        $sep       = \DIRECTORY_SEPARATOR;
        $testFiles = \glob(
            \dirname($testFileBase) . $sep . 'NamespaceBasedClassnameRestrictionsUnitTest{' . $sep . ',' . $sep . '*' . $sep . '}*.inc',
            \GLOB_BRACE
        );

        // Add a file containing the property setting for the tests as the first file.
        \array_unshift($testFiles, $testFileBase . '1.inc');

        // Add a file resetting the property setting for the tests as the last file.
        $testFiles[] = $testFileBase . '2.inc';

        return $testFiles;
    }

    /**
     * Returns the lines where errors should occur.
     *
     * @return array <int line number> => <int number of errors>
     */
    public function getErrorList()
    {
        return [];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * @return array <int line number> => <int number of warnings>
     */
    public function getWarningList()
    {
        return [];
    }
}