<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_quizoff_upgrade($oldversion=0) {
    global $DB;
    
    if ($oldversion < 2017062901) {
        $dbman = $DB->get_manager(); 
        
        // Add new field to quizoff table.
        $table = new xmldb_table('quizoff');
        $field = new xmldb_field('grade');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, false, '0', 'display');
        
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
 
        // Certificate savepoint reached.
        upgrade_mod_savepoint(true, 2017062901, 'quizoff');
    }
}
