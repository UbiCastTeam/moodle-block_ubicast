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
 * Form for editing block_ubicast instances.
 *
 * @package    block_ubicast
 * @copyright  2019 UbiCast {@link https://www.ubicast.eu}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('../../config.php');

/**
 * Form for the ubicast block.
 */
class block_ubicast_edit_form extends block_edit_form {

    /**
     * Specification of the form.
     */
    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        global $CFG;
        $config = get_config('ubicast');

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('block_title', 'block_ubicast'));
        $mform->setDefault('config_title', get_string('pluginname', 'block_ubicast'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('text', 'config_height', get_string('block_height', 'block_ubicast'));
        $mform->setDefault('config_height', 400);
        $mform->setType('config_height', PARAM_INT);

        $types = [
            'c' => get_string('block_types_c', 'block_ubicast'),
            'v' => get_string('block_types_v', 'block_ubicast'),
            'l' => get_string('block_types_l', 'block_ubicast'),
            'p' => get_string('block_types_p', 'block_ubicast'),
        ];
        $select = $mform->addElement('select', 'config_types',
            get_string('block_types', 'block_ubicast'), $types);
        $select->setMultiple(true);
        $mform->setType('config_types', PARAM_TEXT);
        $mform->setDefault('config_types', ['c', 'v', 'l', 'p']);
        $mform->addHelpButton('config_types', 'block_types', 'block_ubicast');

        $orders = [
            'type' => get_string('block_orderby_type_asc', 'block_ubicast'),
            '-type' => get_string('block_orderby_type_desc', 'block_ubicast'),
            'title' => get_string('block_orderby_title_asc', 'block_ubicast'),
            '-title' => get_string('block_orderby_title_desc', 'block_ubicast'),
            'add_date' => get_string('block_orderby_add_asc', 'block_ubicast'),
            '-add_date' => get_string('block_orderby_add_desc', 'block_ubicast'),
            'creation_date' => get_string('block_orderby_creation_asc', 'block_ubicast'),
            '-creation_date' => get_string('block_orderby_creation_desc', 'block_ubicast'),
            'views' => get_string('block_orderby_views_asc', 'block_ubicast'),
            '-views' => get_string('block_orderby_views_desc', 'block_ubicast'),
        ];
        $mform->addElement('select', 'config_orderby',
            get_string('block_orderby', 'block_ubicast'), $orders);
        $mform->setType('config_orderby', PARAM_TEXT);
        $mform->setDefault('config_orderby', '-creation_date');
        $mform->addHelpButton('config_orderby', 'block_orderby', 'block_ubicast');

        // Nudgis content selector (delegated to mod_ubicast). The iframe below is populated by the
        // media selector script, which writes the picked object ID into the config_resourceid field.
        //
        // The block configuration form is a dynamic (AJAX modal) form: its element ids get a random
        // suffix (see the data-random-ids handling in HTML_QuickForm), so we cannot target the
        // fieldset by a fixed id. Instead the script resolves the container at runtime from the
        // stable input name and gives it a predictable id. The media selector script is loaded and
        // MediaSelector is instantiated from its onload callback to guarantee load order, because the
        // fragment inserts this markup with jQuery, which loads external scripts asynchronously.
        $courseid = $this->page->course->id;
        $containerid = 'ubicast_block_selector_' . ($this->block->instance->id ?: 'new');
        $mform->addElement('html', '
            <div class="fitem">
                <div class="felement" style="margin: 0;">
                    <iframe class="ubicast-iframe" style="margin: 0; width: 450px; height: 10px;" src="" frameborder="0"></iframe>
                </div>
            </div>
            <script type="text/javascript">
                (function() {
                    var initMediaSelector = function() {
                        var input = document.querySelector("input[name=config_resourceid]");
                        if (!input || !window.MediaSelector) {
                            return;
                        }
                        var container = input.closest("fieldset") || input.form;
                        if (!container) {
                            return;
                        }
                        container.id = "' . $containerid . '";
                        new window.MediaSelector({
                            moodleURL: "' . $CFG->wwwroot . '/mod/ubicast/lti.php?id=' . $courseid . '",
                            nudgisURL: "' . $config->ubicast_url . '",
                            filterBySpeaker: ' . ($config->ubicast_speakerfilter ? 'true' : 'false') . ',
                            inputName: "config_resourceid",
                            target: "' . $containerid . '"
                        });
                    };
                    if (window.MediaSelector) {
                        initMediaSelector();
                    } else {
                        var script = document.createElement("script");
                        script.type = "text/javascript";
                        script.src = "' . $CFG->wwwroot . '/mod/ubicast/statics/media_selector.js?_=m10";
                        script.onload = initMediaSelector;
                        document.head.appendChild(script);
                    }
                })();
            </script>');

        $mform->addElement('text', 'config_resourceid', get_string('resource_id', 'block_ubicast'),
            ['onchange' => "javascript: this.value = ((new RegExp('(?:^|/)([cvlp][a-z0-9]{19})($:^|/)').exec(this.value)) || [null, this.value])[1]"]);
        $mform->setType('config_resourceid', PARAM_ALPHANUMEXT);
        // An empty global default intentionally leaves the field empty (no fallback).
        $defaultresourceid = get_config('block_ubicast', 'default_resourceid');
        $mform->setDefault('config_resourceid', $defaultresourceid !== false ? $defaultresourceid : '');
        $mform->addHelpButton('config_resourceid', 'resource_id', 'block_ubicast');
    }
}
