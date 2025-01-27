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
 * mod_journal backup moodle 2 structure
 *
 * @package    mod_journal
 * @copyright  2014 David Monllao <david.monllao@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/journal/backup/moodle2/restore_journal_stepslib.php');

/**
 * The restore_journal_activity_task class.
 *
 * @package    mod_journal
 * @copyright  2022 Elearning Software SRL http://elearningsoftware.ro
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_journal_activity_task extends restore_activity_task {

    /**
     * Define the settings for the restore process
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Define the steps for the restore process
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(new restore_journal_activity_structure_step('journal_structure', 'journal.xml'));
    }

    /**
     * Define decode contents for the restore process
     *
     * @return void
     */
    public static function define_decode_contents() {

        $contents = [];
        $contents[] = new restore_decode_content('journal', ['intro'], 'journal');
        $contents[] = new restore_decode_content('journal_entries', ['text', 'entrycomment'], 'journal_entry');

        return $contents;
    }

    /**
     * Define decode rules for the restore process
     *
     * @return void
     */
    public static function define_decode_rules() {

        $rules = [];
        $rules[] = new restore_decode_rule('JOURNALINDEX', '/mod/journal/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('JOURNALVIEWBYID', '/mod/journal/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('JOURNALREPORT', '/mod/journal/report.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('JOURNALEDIT', '/mod/journal/edit.php?id=$1', 'course_module');

        return $rules;

    }

    /**
     * Define decode log rules for the restore process
     *
     * @return void
     */
    public static function define_restore_log_rules() {

        $rules = [];
        $rules[] = new restore_log_rule('journal', 'view', 'view.php?id={course_module}', '{journal}');
        $rules[] = new restore_log_rule('journal', 'view responses', 'report.php?id={course_module}', '{journal}');
        $rules[] = new restore_log_rule('journal', 'add entry', 'edit.php?id={course_module}', '{journal}');
        $rules[] = new restore_log_rule('journal', 'update entry', 'edit.php?id={course_module}', '{journal}');
        $rules[] = new restore_log_rule('journal', 'update feedback', 'report.php?id={course_module}', '{journal}');

        return $rules;
    }

    /**
     * Define decode log rules for the course restore process
     *
     * @return void
     */
    public static function define_restore_log_rules_for_course() {

        $rules = [];
        $rules[] = new restore_log_rule('journal', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
