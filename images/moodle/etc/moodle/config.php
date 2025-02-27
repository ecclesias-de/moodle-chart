<?php

unset($CFG);
global $CFG;
$CFG = new stdClass();

#TODO use env variables
$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'username';
$CFG->dbpass    = 'password';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array(
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => '',
    'dbhandlesoptions' => false,
    'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'http://example.com/moodle';

$CFG->dataroot  = '/var/lib/moodle';
$CFG->localcachedir = '/var/run/moodle/cache';

$CFG->routerconfigured = true;

$CFG->directorypermissions = 02777;

$CFG->admin = 'admin';

$CFG->xsendfile = 'X-Accel-Redirect';
$CFG->xsendfilealiases = array(
    '/dataroot/' => $CFG->dataroot,
    '/localcachedir/' => $CFG->localcachedir,
);

$CFG->upgradekey = 'put_some_password-like_value_here';

require_once(__DIR__ . '/lib/setup.php');