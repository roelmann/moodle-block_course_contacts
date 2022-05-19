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
 * Block Course_Contacts plugin lib
 *
 * @package     block_course_contacts
 * @author      Kevin Pham <kevinpham@catalyst-au.net>
 * @copyright   Catalyst IT, 2022
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


function block_course_contacts_before_http_headers() {
    global $PAGE, $DB;

    // Only display for course-info page
    // TODO: Store page type(s) allowed for as a global setting?
    if ($PAGE->pagetype !== 'course-info') {
        return;
    }

    // Enabled check.
    $autocreateoninfopage = get_config('block_course_contacts', 'autocreateoninfopage');
    if (empty($autocreateoninfopage)) {
        return;
    }

    // Only display one instance of this block - if one has been set up for this page already, do not display it.
    $blockexists = $DB->record_exists('block_instances', [
        'parentcontextid' => $PAGE->context->id,
        'blockname' => 'course_contacts'
    ]);
    if ($blockexists) {
        return;
    }

    // Manually initialise the block since the auto assignment of context by instance id does not exist.
    $blockinstance = (object) [
        'blockname'         => 'course_contacts',
        'showinsubcontexts' => '0',
        'pagetypepattern'   => 'course-info',
        'subpagepattern'    => null,
        'defaultregion'     => 'side-pre',
        'defaultweight'     => '0',
    ];
    $blockmanager = $PAGE->blocks;
    $blockmanager->add_block(
        $blockinstance->blockname,
        $blockinstance->defaultregion,
        $blockinstance->defaultweight,
        $blockinstance->showinsubcontexts,
        $blockinstance->pagetypepattern,
        $blockinstance->subpagepattern
    );
}
