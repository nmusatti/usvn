<?php
/**
 * Import users from an htpasswd file to USVN
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.7
 * @package admin
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id: ImportHtpasswdTest.php 1188 2007-10-06 12:03:17Z dolean_j $
 */

// Call USVN_ImportSVNRepositoriesTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "USVN_ImportSVNRepositoriesTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

define('CONFIG_FILE', 'tests/test.ini');
require_once 'www/USVN/autoload.php';

/**
 * Test class for USVN_ImportSVNRepositoriesTest.
 * Generated by PHPUnit_Util_Skeleton on 2007-04-03 at 09:22:11.
 */
class USVN_ImportSVNRepositoriesTest extends USVN_Test_DB {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("USVN_ImportSVNRepositoriesTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

	public function tearDown() {
		parent::tearDown();
	}

	public function testCanBeImportedBadNotDir()
	{
		$this->assertFalse(USVN_ImportSVNRepositories::canBeImported('truc'));
	}

	public function testCanBeImportedBadNotSVNRepo()
	{
		$path = 'tests/tmp/svn/testSVN/';
		mkdir($path);
		$this->assertFalse(USVN_ImportSVNRepositories::canBeImported($path));
		USVN_DirectoryUtils::removeDirectory($path);
	}

	public function testCanBeImportedBadNotInclude()
	{
		$path = 'tests/testSVN';
		USVN_SVNUtils::createSvn($path);

		$this->assertFalse(USVN_ImportSVNRepositories::canBeImported($path));
		USVN_DirectoryUtils::removeDirectory($path);
	}

	public function testCanBeImportedOk()
	{
		$path = 'tests/tmp/svn/testSVN';
		USVN_SVNUtils::createSvn($path);
		$this->assertTrue(USVN_ImportSVNRepositories::canBeImported($path, array('verbose' => true)));
		USVN_DirectoryUtils::removeDirectory($path);
	}

	public function testLookAfterSVNRepositoriesToImportNoResult()
	{
		$path = 'tests/tmp/svn/test/';
		mkdir($path);
		mkdir($path.'test');
		mkdir($path.'test2');
		mkdir($path.'test3');
		$result = USVN_ImportSVNRepositories::lookAfterSVNRepositoriesToImport($path);
		if (count($result)) {
			$this->fail();
		}
		USVN_DirectoryUtils::removeDirectory($path);
	}

	public function testLookAfterSVNRepositoriesToImportListOk()
	{
		$path = 'tests/tmp/svn/test/';
		mkdir($path);
		USVN_SVNUtils::createSvn($path.'test');
		USVN_SVNUtils::createSvn($path.'test2');
		mkdir($path.'test3');
		USVN_SVNUtils::createSvn($path.'test3/test3');
		$result = USVN_ImportSVNRepositories::lookAfterSVNRepositoriesToImport($path);
		if (count($result) != 2) {
			$this->fail();
		}
		USVN_DirectoryUtils::removeDirectory($path);
	}

	public function testLookAfterSVNRepositoriesToImportRecursiveListOk()
	{
		$path = 'tests/tmp/svn/test/';
		mkdir($path);
		USVN_SVNUtils::createSvn($path.'test');
		USVN_SVNUtils::createSvn($path.'test2');
		mkdir($path.'test3');
		USVN_SVNUtils::createSvn($path.'/test3/test3');
		$options = array('recursive' => true);
		$result = USVN_ImportSVNRepositories::lookAfterSVNRepositoriesToImport($path, $options);
		if (count($result) != 3) {
			$this->fail();
		}
		USVN_DirectoryUtils::removeDirectory($path);
	}

	public function testImportSVNRepositoriesOk()
	{
		try {
			$table = new USVN_Db_Table_Users();
			$obj = $table->fetchNew();
			$obj->setFromArray(array('users_login' 			=> 'user_test',
										'users_password' 	=> 'password',
										'users_firstname' 	=> 'firstname',
										'users_lastname' 	=> 'lastname',
										'users_email' 		=> 'email@email.fr'));
			$obj->save();
		}
		catch (USVN_Exception $e) {
			print $e->getMessage()."\n";
			$this->fail();
		}

		$path = 'tests/tmp/svn/test/';
		mkdir($path);
		USVN_SVNUtils::createSvn($path.'test');
		USVN_SVNUtils::createSvn($path.'test2');
		mkdir($path.'test3');
		USVN_SVNUtils::createSvn($path.'test3/test3');
		$options = array('recursive' => true, 'login' => 'user_test');
		$imp = new USVN_ImportSVNRepositories();
		$results = $imp->lookAfterSVNRepositoriesToImport($path, $options);
		if (count($results) != 3) {
			$this->fail();
		}
		$imp->addSVNRepositoriesToImport($results, $options);
		try {
			$imp->importSVNRepositories();
		}
		catch (USVN_Exception $e) {
			print $e->getMessage()."\n";
			$this->fail();
		}
		USVN_DirectoryUtils::removeDirectory($path);
	}
}

// Call USVN_ImportSVNRepositoriesTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_ImportSVNRepositoriesTest::main") {
    USVN_ImportSVNRepositoriesTest::main();
}
?>
