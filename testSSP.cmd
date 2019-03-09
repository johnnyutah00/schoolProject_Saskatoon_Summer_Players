@echo off
echo "starting tests. please ensure that the envTestScript.bash has been executed and is running"

::note - bash files and batch files hate each other. Without additional software, two script files is like the best we can do for running tests (envTestScript.bash and testSSP.cmd)

::note - ALL "require_once" STATEMENTS AT THE BEGINNING OF PHP FILES MUST BE REMOVED. The Framework.php that makes use of the "require_once" statements was removed back in PHPUnit v3.6,
::and now throws errors whenever encountered via command-line use. So just remove them, okay bruh?!

::note - want to know what the test output in the command prompt means? check out this link -> https://phpunit.de/manual/6.5/en/textui.html

::code breakdown:

	::@echo off at the start of the file removes the echoing of commands to the command prompt as they are being executed; makes things cleaner

	::"start" runs a command in the background, while "call" safely runs a script in the background, waiting for it to finished before returning to the current one. 
	:: Try using start to run multiple scripts at once and things get messed up in this instance.
	
	::phpunit invokes the phpunit.batch script located in C:\Program Files (x86)\PHP\phar
	
	::--testsuite configures the test configuration options, to which we are pointing it to the phpunit configuration file located at the root of the SSP project
	
	::lastly, we need the path to the test we want to run. You can also provide the path to a folder of tests to run and PHPUnit will run everything contained within.
	::if you are providing the path to a folder, all files contained within must end in "Test.php" in order to be recognized and run.

start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
	
::note that I have no idea why a link to saskpolytech prints out randomly during the show controller test. I edited the file where the link is and nothing changed. It doesn't affect anything tho, so...
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\AddUpdateDeleteShowTests.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\AuditionDetailsControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\MemberControllerSearchTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\MemberControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\MemberControllerViewBoardTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\Controller/MemberControllerViewEmptyBoardTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\MemberLoginControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\MemberVolunteerControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\OnlinePollControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\ShowControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\ShowUploadImageTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\SuggestedSSPShowControllerTest.php
call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\PasswordResetControllerTest.php

::ADD YOUR NEW TEST CLASS HERE
::call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Controller\NAMEOFYOURTESTCLASS.php

call D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Entity

D:\PHP\phar\phpunit --testsuite D:\prj4.ssp\ssp_project\phpunit.xml.dist D:\prj4.ssp\ssp_project\tests\Service