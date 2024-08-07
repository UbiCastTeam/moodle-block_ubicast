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
 * block_ubicast LTI view
 *
 * @package    block_ubicast
 * @copyright  2019 UbiCast {@link https://www.ubicast.eu}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/ubicast/lib.php');
require_once($CFG->dirroot . '/mod/ubicast/locallib.php');

$cid = required_param('id', PARAM_INT);  // Course ID.
$oid = required_param('oid', PARAM_ALPHANUMEXT);  // Media or channel object ID.
$orderby = optional_param('orderBy', null, PARAM_TEXT);
$filters = optional_param('filters', null, PARAM_TEXT);

$querystring = '';
if ($orderby) {
    $querystring .= '&orderBy=' . $orderby;
}
if ($filters) {
    $querystring .= '&filters=' . urlencode($filters);
}

$course = $DB->get_record('course', ['id' => $cid], '*', MUST_EXIST);

$context = context_course::instance($cid);

require_login($course, true);
require_capability('mod/ubicast:view', $context);

ubicast_launch_tool($course, null, $oid . '/?newtab' . $querystring);
