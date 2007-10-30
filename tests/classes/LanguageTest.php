<?php
// Call LanguageTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'LanguageTest::main');
}

require_once 'PHPUnit/Framework.php';

if (!defined('APPLICATION_HOME')) { include dirname(__FILE__).'/../configuration.inc'; }
require_once APPLICATION_HOME.'/classes/Language.inc';

/**
 * Test class for Language.
 * Generated by PHPUnit on 2007-10-04 at 11:49:08.
 */
class LanguageTest extends PHPUnit_Framework_TestCase {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('LanguageTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    	$PDO = Database::getConnection();
    	$query = $PDO->prepare('insert languages (id,code,english,native) values(?,?,?,?)');
    	$query->execute(array(100,'te','Test','Test Native'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    	$PDO = Database::getConnection();
    	$query = $PDO->prepare('delete from languages where code=?');
    	$query->execute(array('te'));
    }

    /**
     * Test creating empty object
     */
    public function testCreateNewLanguage() {
    	$lang = new Language();
    	$this->assertTrue($lang instanceof Language, "expected Language object");
    }

	/**
	 * Test loading an existing language from the database by id
	 */
    public function testLoadById()
    {
		$PDO = Database::getConnection();

		$lang = new Language(100);
		$this->assertTrue($lang->getEnglish()=='Test','Could not load the test Language object');
    }

    /**
     * Test loading an existing language from the database by code
     */
    public function testLoadByCode() {
		$PDO = Database::getConnection();

    	$lang = new Language('te');
    	$this->assertTrue($lang->getEnglish()=='Test',"Couldn't load the Test Language object");
    }

    /**
     * Test modifying an existing row in the database
     */
    public function testUpdate() {
    	$test = new Language('te');
    	$test->setEnglish('changed');
    	$test->save();

    	$PDO = Database::getConnection();
    	$query = $PDO->prepare('select english from languages where code=?');
    	$query->execute(array('te'));
    	$result = $query->fetchAll();
    	$this->assertEquals($result[0]['english'],'changed','Language did not get updated in the database');
    }

    /**
     * Test creating a new row in the database
     */
    public function testInsert() {
    	$new = new Language();
    	$new->setCode('ne');
    	$new->setEnglish('New');
    	$new->setNative('Native');
    	$new->save();

    	$PDO = Database::getConnection();
    	$query = $PDO->query("select * from languages where code='ne'");
    	$result = $query->fetchAll();
    	$this->assertEquals($result[0]['code'],'ne','New Language did not get created');
    	$this->assertEquals($result[0]['english'],'New','New Language did not get created');
    	$this->assertEquals($result[0]['native'],'Native','New Language did not get created');

    	$PDO->exec("delete from languages where code='ne'");
    }
}


// Call LanguageTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'LanguageTest::main') {
    LanguageTest::main();
}
?>
