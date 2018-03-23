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
 * Workflow form processing unit tests.
 *
 * @package    tool_trigger
 * @copyright  Matt Porritt <mattp@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * Workflow form processing unit tests.
 *
 * @package     tool_trigger
 * @copyright   Matt Porritt <mattp@catalyst-au.net>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_trigger_workflow_process_testcase extends advanced_testcase {

    /**
     * Test workflow process form data
     */
    public function test_processform() {
        $this->resetAfterTest();

        $mdata = new \stdClass();

        $workflowprocess = new \tool_trigger\workflow_process($mdata);
        //$result = $workflowprocess->processform();

       // $this->assertTrue($result);
    }

    /**
     * Test workflow process step json data.
     */
    public function test_processjson() {
        $mdata = new \stdClass();
        $json = '[[{"name":"sesskey","value":"5IfqXAV9A5"},{"name":"_qf__tool_trigger_steps_base_base_form","value":"1"},'
                .'{"name":"steptype","value":"trigger"},{"name":"step","value":"/steps/trigger/log_step"},'
                .'{"name":"stepname","value":"test step"},{"name":"stepdescription","value":"test step description"}]]';
        $now = 1521773594;

        $expected = new \stdClass();
        $expected->workflowid = 1;
        $expected->timecreated = $now;
        $expected->timemodified = $now;
        $expected->steptype = 'trigger';
        $expected->step = '/steps/trigger/log_step';
        $expected->stepname = 'test step';
        $expected->stepdescription = 'test step description';

        $workflowprocess = new \tool_trigger\workflow_process($mdata);
        $result = $workflowprocess->processjson($json, 1, $now);

        $this->assertEquals($expected, $result[0]);
    }

    /**
     * Test workflow process step json data with mulitple steps.
     */
    public function test_processjson_multiple_steps() {
        $mdata = new \stdClass();
        $json = '[[{"name":"sesskey","value":"uONxhfLknA"},{"name":"_qf__tool_trigger_steps_base_base_form","value":"1"},'
                .'{"name":"steptype","value":"trigger"},{"name":"step","value":"/steps/trigger/log_step"},'
                .'{"name":"stepname","value":"step 1 name"},{"name":"stepdescription","value":"step 1 description"}],'
                .'[{"name":"sesskey","value":"uONxhfLknA"},{"name":"_qf__tool_trigger_steps_base_base_form","value":"1"},'.
                '{"name":"steptype","value":"trigger"},{"name":"step","value":"/steps/trigger/log_step"},'
                .'{"name":"stepname","value":"step 2 name"},{"name":"stepdescription","value":"step 2 description"}]]';
        $now = 1521773594;

        $expected1 = new \stdClass ();
        $expected1->workflowid = 1;
        $expected1->timecreated = $now;
        $expected1->timemodified = $now;
        $expected1->steptype = 'trigger';
        $expected1->step = '/steps/trigger/log_step';
        $expected1->stepname = 'step 1 name';
        $expected1->stepdescription = 'step 1 description';

        $expected2 = new \stdClass ();
        $expected2->workflowid = 1;
        $expected2->timecreated = $now;
        $expected2->timemodified = $now;
        $expected2->steptype = 'trigger';
        $expected2->step = '/steps/trigger/log_step';
        $expected2->stepname = 'step 2 name';
        $expected2->stepdescription = 'step 2 description';

        $workflowprocess = new \tool_trigger\workflow_process ($mdata);
        $result = $workflowprocess->processjson ($json, 1, $now);

        $this->assertEquals ($expected1, $result[0]);
        $this->assertEquals ($expected2, $result[1]);
    }
}