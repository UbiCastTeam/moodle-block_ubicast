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
 * block_ubicast display.
 *
 * @package    block_ubicast
 * @copyright  2019 UbiCast {@link https://www.ubicast.eu}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class block_ubicast extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_ubicast');
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return false;
    }

    public function applicable_formats() {
        return array('all' => true, 'my' => false);
    }

    public function instance_allow_config() {
        return true;
    }

    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_ubicast');
        } else {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        global $CFG, $SITE, $USER, $DB, $COURSE;

        if (isloggedin()) {
            $systemcontext = context_system::instance();

            $itemoid = $this->config->resourceid;

            if ($itemoid) {
                $url = $CFG->wwwroot.'/blocks/ubicast/lti.php?id='.$COURSE->id.'&oid='.$itemoid;
                $this->content = new stdClass();
                $this->content->text = '<iframe id="contentframe" height="'.$this->config->height.'px" width="100%" src="'.$url.'"';
                $this->content->text += ' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                $this->content->footer = '';
            } else {
                if (has_capability('moodle/site:manageblocks', $systemcontext)) {
                    $this->content->text = get_string('unconfigured_message', 'block_ubicast');
                    $this->content->footer = '';
                }
            }
            return $this->content;
        }
    }
}
