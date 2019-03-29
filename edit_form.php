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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Form for editing block_ubicast instances.
 *
 * @package    block_ubicast
 * @copyright  Stéphane Diemer
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_ubicast_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        
        $mform->addElement('text', 'config_title', get_string('block_title', 'block_ubicast'));
        $mform->setDefault('config_title', get_string('pluginname', 'block_ubicast'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('text', 'config_height', get_string('block_height', 'block_ubicast'));
        $mform->setDefault('config_height', 400);
        $mform->setType('config_height', PARAM_INT);

        $mform->addElement('text', 'config_resourceid', get_string('resource_id', 'block_ubicast'));
        $mform->addHelpButton('config_resourceid', 'resource_id', 'block_ubicast');
        $mform->setType('config_resourceid', PARAM_ALPHANUMEXT);
    }
}
