<?php
unset($CFG);
global $CFG;
$CFG = new stdClass();
$CFG->dbtype = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost = 'postgres';
$CFG->dbname = 'moodle';
$CFG->dbuser = 'postgres';
$CFG->dbpass = '';
$CFG->prefix = 'mdl_';
$CFG->dboptions = ['dbcollation' => 'utf8mb4_unicode_ci'];
$CFG->sxs_config = '';
$CFG->wwwroot = 'http://localhost/moodle';
$CFG->dataroot  = '/var/moodledata';
$CFG->directorypermissions = 02777;
$CFG->admin = 'admin';
$CFG->passwordpolicy = 0;

$CFG->session_handler_class = '\core\session\redis';
$CFG->session_redis_host = '127.0.0.1';
$CFG->session_redis_host = 'redis';

$CFG->debug = (E_ALL | E_STRICT); // DEBUG_DEVELOPER
$CFG->debugdisplay = 1;
$CFG->perfdebug = 15;
$CFG->debugpageinfo = 1;

$CFG->phpunit_dataroot = '/var/phpunitdata';
$CFG->phpunit_prefix = 'phpu_';

$CFG->behat_wwwroot = 'http://nginx/moodle';
$CFG->behat_dataroot = '/var/moodledata_behat';
$CFG->behat_prefix = 'bht_';
$CFG->behat_profiles = array(
    'default' => array(
        'browser' => 'firefox',
        'wd_host' => 'http://selenium:4444/wd/hub',
    ),
);
$CFG->behat_faildump_path = '/var/moodle_behat_output';

require_once(__DIR__ . '/lib/setup.php');
