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
 * Form for editing Channel block instances.
 *
 * @package    block_channel
 * @copyright  Parthajeet Chakraborty (parthajeet@dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing Blog tags block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_channel_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        

					$mform->addElement('text', 'config_title', get_string('configtitle', 'block_channel'));
					$mform->setDefault('config_title', get_string('pluginname', 'block_channel'));
					$mform->setType('config_title', PARAM_TEXT);
					
					
					$mform->addElement('text', 'config_launchurl', get_string('launchurl', 'block_channel'));
					$mform->setType('config_launchurl', PARAM_TEXT);
					
				
				/*
				if(is_siteadmin()) {
					
					$mform->addElement('text', 'config_key', get_string('key', 'block_channel'));
					$mform->setType('config_key', PARAM_TEXT);
					
					$mform->addElement('text', 'config_secret', get_string('secret', 'block_channel'));
					$mform->setType('config_secret', PARAM_TEXT);
        
        }
        */
        
        $mform->addElement('text', 'config_height', get_string('height', 'block_channel'));
        $mform->setDefault('config_height', 400);
				$mform->setType('config_height', PARAM_TEXT);
        

    }
}
