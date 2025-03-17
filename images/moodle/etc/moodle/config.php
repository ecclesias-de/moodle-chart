<?php

function getenvThrow($varname) {
    $value = getenv($varname);
    if ($value === false) {
        throw new Exception("OCI Container config error: missing environment variable '$varname'.");
    }

    return $value;
}

function getenvDefault($varname, $default) {
    $value = getenv($varname);
    if ($value === false) {
        return $default;
    }

    return $value;
}

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenvThrow('POSTGRES_HOST');
$CFG->dbname    = getenvThrow('POSTGRES_DB');
$CFG->dbuser    = getenvThrow('POSTGRES_USER');
$CFG->dbpass    = getenvThrow('POSTGRES_PASSWORD');
$CFG->prefix    = getenvDefault('MOODLE_PREFIX', 'mdl_');
$CFG->dboptions = array(
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => '',
    'dbhandlesoptions' => false,
    'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->session_handler_class = '\core\session\redis';
$CFG->session_redis_host = getenvThrow('REDIS_HOST');
$CFG->session_redis_port = getenvDefault('REDIS_PORT', '6379');
$CFG->session_redis_database = getenvDefault('REDIS_DB', '0');

# moodle url e.g. https://moodle.example.org
# set by image
$CFG->wwwroot   = getenvThrow('MOODLE_WWWROOT');

# set by image
$CFG->dataroot  = getenvThrow('MOODLE_DATA');
# set by image
$CFG->localcachedir = getenvThrow('MOODLE_LOCAL_CACHE');;

$CFG->routerconfigured = true;
$CFG->sslproxy = true;

$CFG->directorypermissions = 02777;

$CFG->admin = getenvDefault('MOODLE_ADMIN', 'admin');

$CFG->xsendfile = 'X-Accel-Redirect';
$CFG->xsendfilealiases = array(
    '/dataroot/' => $CFG->dataroot,
    '/localcachedir/' => $CFG->localcachedir,
);

$CFG->upgradekey = getenvThrow('MOODLE_UPGRADEKEY');

$CFG->preventexecpath = true;

require_once(__DIR__ . '/lib/setup.php');