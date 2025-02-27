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

$CFG->dbtype    = getenvDefault('MOODLE_DBTYPE', 'pgsql');
$CFG->dblibrary = getenvDefault('MOODLE_DBLIBRARY', 'native');
$CFG->dbhost    = getenvThrow('MOODLE_DBHOST');
$CFG->dbname    = getenvThrow('MOODLE_DBNAME');
$CFG->dbuser    = getenvThrow('MOODLE_DBUSER');
$CFG->dbpass    = getenvThrow('MOODLE_DBPASSWORD');
$CFG->prefix    = getenvDefault('MOODLE_PREFIX', 'mdl_');
$CFG->dboptions = array(
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => '',
    'dbhandlesoptions' => false,
    'dbcollation' => 'utf8mb4_unicode_ci',
);

# moodle url e.g. https://moodle.example.org
# set by image
$CFG->wwwroot   = getenvThrow('MOODLE_ROOT');

# set by image
$CFG->dataroot  = getenvThrow('MOODLE_DATA');
# set by image
$CFG->localcachedir = getenvThrow('MOODLE_LOCAL_CACHE');;

$CFG->routerconfigured = true;

$CFG->directorypermissions = 02777;

$CFG->admin = getenvDefault('MOODLE_ADMIN', 'admin');

$CFG->xsendfile = 'X-Accel-Redirect';
$CFG->xsendfilealiases = array(
    '/dataroot/' => $CFG->dataroot,
    '/localcachedir/' => $CFG->localcachedir,
);

$CFG->upgradekey = getenvThrow('MOODLE_UPGRADEKEY');

require_once(__DIR__ . '/lib/setup.php');