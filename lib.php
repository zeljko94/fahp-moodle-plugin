<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants for module testnimodul
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the testnimodul specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_testnimodul
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Example constant, you probably want to remove this :-)
 */
define('WIDGET_ULTIMATE_ANSWER', 42);

/* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function testnimodul_supports($feature) {

    switch($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the testnimodul into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $testnimodul Submitted data from the form in mod_form.php
 * @param mod_testnimodul_mod_form $mform The form instance itself (if needed)
 * @return int The id of the newly inserted testnimodul record
 */
function testnimodul_add_instance(stdClass $testnimodul, mod_testnimodul_mod_form $mform = null) {
    global $DB;

    $testnimodul->timecreated = time();

    // You may have to add extra stuff in here.

    $testnimodul->id = $DB->insert_record('testnimodul', $testnimodul);

    testnimodul_grade_item_update($testnimodul);

    return $testnimodul->id;
}

/**
 * Updates an instance of the testnimodul in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $testnimodul An object from the form in mod_form.php
 * @param mod_testnimodul_mod_form $mform The form instance itself (if needed)
 * @return boolean Success/Fail
 */
function testnimodul_update_instance(stdClass $testnimodul, mod_testnimodul_mod_form $mform = null) {
    global $DB;

    $testnimodul->timemodified = time();
    $testnimodul->id = $testnimodul->instance;

    // You may have to add extra stuff in here.

    $result = $DB->update_record('testnimodul', $testnimodul);

    testnimodul_grade_item_update($testnimodul);

    return $result;
}

/**
 * This standard function will check all instances of this module
 * and make sure there are up-to-date events created for each of them.
 * If courseid = 0, then every testnimodul event in the site is checked, else
 * only testnimodul events belonging to the course specified are checked.
 * This is only required if the module is generating calendar events.
 *
 * @param int $courseid Course ID
 * @return bool
 */
function testnimodul_refresh_events($courseid = 0) {
    global $DB;

    if ($courseid == 0) {
        if (!$testnimoduls = $DB->get_records('testnimodul')) {
            return true;
        }
    } else {
        if (!$testnimoduls = $DB->get_records('testnimodul', array('course' => $courseid))) {
            return true;
        }
    }

    foreach ($testnimoduls as $testnimodul) {
        // Create a function such as the one below to deal with updating calendar events.
        // testnimodul_update_events($testnimodul);
    }

    return true;
}

/**
 * Removes an instance of the testnimodul from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function testnimodul_delete_instance($id) {
    global $DB;

    if (! $testnimodul = $DB->get_record('testnimodul', array('id' => $id))) {
        return false;
    }

    // Delete any dependent records here.

    $DB->delete_records('testnimodul', array('id' => $testnimodul->id));

    testnimodul_grade_item_delete($testnimodul);

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 *
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param cm_info|stdClass $mod The course module info object or record
 * @param stdClass $testnimodul The testnimodul instance record
 * @return stdClass|null
 */
function testnimodul_user_outline($course, $user, $mod, $testnimodul) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * It is supposed to echo directly without returning a value.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $testnimodul the module instance record
 */
function testnimodul_user_complete($course, $user, $mod, $testnimodul) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in testnimodul activities and print it out.
 *
 * @param stdClass $course The course record
 * @param bool $viewfullnames Should we display full names
 * @param int $timestart Print activity since this timestamp
 * @return boolean True if anything was printed, otherwise false
 */
function testnimodul_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link testnimodul_print_recent_mod_activity()}.
 *
 * Returns void, it adds items into $activities and increases $index.
 *
 * @param array $activities sequentially indexed array of objects with added 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 */
function testnimodul_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@link testnimodul_get_recent_mod_activity()}
 *
 * @param stdClass $activity activity record with added 'cmid' property
 * @param int $courseid the id of the course we produce the report for
 * @param bool $detail print detailed report
 * @param array $modnames as returned by {@link get_module_types_names()}
 * @param bool $viewfullnames display users' full names
 */
function testnimodul_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 *
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * Note that this has been deprecated in favour of scheduled task API.
 *
 * @return boolean
 */
function testnimodul_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * For example, this could be array('moodle/site:accessallgroups') if the
 * module uses that capability.
 *
 * @return array
 */
function testnimodul_get_extra_capabilities() {
    return array();
}

/* Gradebook API */

/**
 * Is a given scale used by the instance of testnimodul?
 *
 * This function returns if a scale is being used by one testnimodul
 * if it has support for grading and scales.
 *
 * @param int $testnimodulid ID of an instance of this module
 * @param int $scaleid ID of the scale
 * @return bool true if the scale is used by the given testnimodul instance
 */
function testnimodul_scale_used($testnimodulid, $scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('testnimodul', array('id' => $testnimodulid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of testnimodul.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale
 * @return boolean true if the scale is used by any testnimodul instance
 */
function testnimodul_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('testnimodul', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the given testnimodul instance
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $testnimodul instance object with extra cmidnumber and modname property
 * @param bool $reset reset grades in the gradebook
 * @return void
 */
function testnimodul_grade_item_update(stdClass $testnimodul, $reset=false) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    $item = array();
    $item['itemname'] = clean_param($testnimodul->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;

    if ($testnimodul->grade > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax']  = $testnimodul->grade;
        $item['grademin']  = 0;
    } else if ($testnimodul->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = -$testnimodul->grade;
    } else {
        $item['gradetype'] = GRADE_TYPE_NONE;
    }

    if ($reset) {
        $item['reset'] = true;
    }

    grade_update('mod/testnimodul', $testnimodul->course, 'mod', 'testnimodul',
            $testnimodul->id, 0, null, $item);
}

/**
 * Delete grade item for given testnimodul instance
 *
 * @param stdClass $testnimodul instance object
 * @return grade_item
 */
function testnimodul_grade_item_delete($testnimodul) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/testnimodul', $testnimodul->course, 'mod', 'testnimodul',
            $testnimodul->id, 0, null, array('deleted' => 1));
}

/**
 * Update testnimodul grades in the gradebook
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $testnimodul instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 */
function testnimodul_update_grades(stdClass $testnimodul, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    // Populate array of grade objects indexed by userid.
    $grades = array();

    grade_update('mod/testnimodul', $testnimodul->course, 'mod', 'testnimodul', $testnimodul->id, 0, $grades);
}

/* File API */

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function testnimodul_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for testnimodul file areas
 *
 * @package mod_testnimodul
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function testnimodul_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the testnimodul file areas
 *
 * @package mod_testnimodul
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the testnimodul's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function testnimodul_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

/* Navigation API */

/**
 * Extends the global navigation tree by adding testnimodul nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the testnimodul module instance
 * @param stdClass $course current course record
 * @param stdClass $module current testnimodul instance record
 * @param cm_info $cm course module information
 */
function testnimodul_extend_navigation(navigation_node $navref, stdClass $course, stdClass $module, cm_info $cm) {
    // TODO Delete this function and its docblock, or implement it.
}

/**
 * Extends the settings navigation with the testnimodul settings
 *
 * This function is called when the context for the page is a testnimodul module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav complete settings navigation tree
 * @param navigation_node $testnimodulnode testnimodul administration node
 */
function testnimodul_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $testnimodulnode=null) {
    // TODO Delete this function and its docblock, or implement it.
}
