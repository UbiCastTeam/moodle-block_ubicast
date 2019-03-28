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
 * Channel block.
 *
 * @package    block_channel
 * @copyright  Parthajeet Chakraborty (parthajeet@dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class block_channel extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_channel');
    }

    function instance_allow_multiple() {
        return true;
    }

    function has_config() {
        return false;
    }

    function applicable_formats() {
        return array('all' => true, 'my' => false);
    }

    function instance_allow_config() {
        return true;
    }

    function specialization() {

        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_channel');
        } else {
            $this->title = $this->config->title;
        }
    }

    function get_content() {
        global $CFG, $SITE, $USER, $DB, $COURSE;
        
        if (isloggedin()) {
        
					$systemcontext = context_system::instance();
					
					
					$config = get_config('easycastms');
					$key = $config->easycastms_ltikey;
					$secret = $config->easycastms_ltisecret;
					
					
					//$key = $this->config->key;
					//$secret = $this->config->secret;
					$launch_url = $this->config->launchurl;
	
					if($launch_url && $key && $secret) {
						if(is_siteadmin()) {
							$roles="Instructor,urn:lti:sysrole:ims/lis/Administrator,urn:lti:instrole:ims/lis/Administrator";
						} else {
							$context = $coursecontext = context_course::instance($COURSE->id);
							$roles = get_user_roles($context, $USER->id, true);
							if($roles) {
								foreach($roles as $role) {
									if($role->shortname == "student") {
										$roles = 'Learner';
									} 
									
									if($role->shortname == "editingteacher") {
										$roles = 'Instructor';
									}
									
									if($role->shortname == "teacher") {
										$roles = 'Learner';
									}
									
									if($role->shortname == "manager") {
										$roles = 'Instructor';
									}
								}
							} else {
								$roles = 'Learner';
							}
						}

						$url = $CFG->wwwroot.'/blocks/channel/launch.php?launch_url='.$launch_url.'&key='.$key.'&secret='.$secret.'&user_id='.$USER->id.'&context_id='.$COURSE->id.'&roles='.$roles;
						$this->content = new stdClass();
						$this->content->text = '<iframe id="contentframe" height="'.$this->config->height.'px" width="100%" src="'.$url.'" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
						$this->content->footer = '';
						
					} else {
						if (has_capability('moodle/site:manageblocks', $systemcontext)) {
							$this->content->text = get_string('configure_error', 'block_channel');
							$this->content->footer = '';
						}
					}
					return $this->content;
        }
    }
}



