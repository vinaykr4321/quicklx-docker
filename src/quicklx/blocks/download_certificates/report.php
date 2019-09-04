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
 * Version details
 *
 * Download certificates block
 * --------------------------
 * Displays all issued certificates for users with unique codes.
 * The certificates will also be issued for courses that have been archived since issuing of the certificates.
 * All previously issued certificates can be downloaded as Zipped file. Contributed by Neeraj KP (kpneeraj).
 *
 * @copyright  2015 onwards Manieer Chhettri | <manieer@gmail.com>
 * @author     Manieer Chhettri | <manieer@gmail.com> | 2015
 * @package    block_download_certificates
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot . '/mod/certificate/locallib.php');
require_once('bulkcertificate_form.php');
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/download_certificates/script.js'), true);

require_login();

$url = new moodle_url('/blocks/download_certificates/report.php');
$strcertificates = get_string('download_certificates_modulenameplural', 'block_download_certificates');
$PAGE->set_pagelayout('standard');
$PAGE->set_url($url);

// Check capabilities.
$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->navbar->add($strcertificates);
$PAGE->set_title($strcertificates);
$PAGE->set_heading($strcertificates);

echo $OUTPUT->header();
$bulkcertificate = new bulkcertificate_form();
if (isset($_POST['submitbutton'])) {

    $check = false;
    $courseids = $_POST['course'];
    $ids = implode(',', $courseids);
    $course = $DB->get_records_sql("SELECT u.id as userid,u.firstname,u.username,course.id as course FROM {course} AS course 
                                JOIN {enrol} AS en ON en.courseid = course.id 
                                JOIN {user_enrolments} AS ue ON ue.enrolid = en.id 
                                JOIN {user} AS u ON ue.userid = u.id
                                where course.id IN ($ids)");

    if ($course) {
        $table = new html_table();
        $table->head = array('User Name', get_string('download_certificates_tblheader_coursename', 'block_download_certificates'),
            get_string('download_certificates_tblheader_grade', 'block_download_certificates'),
            get_string('download_certificates_tblheader_code', 'block_download_certificates'),
            get_string('download_certificates_tblheader_issuedate', 'block_download_certificates'),
            get_string('download_certificates_tblheader_download', 'block_download_certificates'));
        $table->align = array("left", "center", "center", "center", "center");

        foreach ($course as $user) {

            $sql = "SELECT f.id AS fid, f.userid AS fuserid, u.firstname, u.lastname, f.contextid AS fcontextid, f.filename AS ffilename,
                       ctx.id AS ctxid, ctx.contextlevel AS ctxcontextlevel, ctx.instanceid AS ctxinstanceid,
                       cm.id AS cmid, cm.course AS cmcourse, cm.module AS cmmodule, cm.instance AS cminstance,
                       crt.id AS crtid, crt.course AS crtcourse, crt.name AS crtname, ci.id AS ciid,
					   ci.userid AS ciuserid, ci.certificateid AS cicertificateid, ci.code AS cicode,
					   ci.timecreated AS citimecreated, c.id AS cid, c.fullname AS cfullname,
					   c.shortname AS cshortname
                    FROM {files} f
                    INNER JOIN {context} ctx
                            ON ctx.id = f.contextid
                    INNER JOIN {course_modules} cm
                            ON cm.id = ctx.instanceid
                    INNER JOIN {certificate} crt
                            ON crt.id = cm.instance
                     LEFT JOIN {certificate_issues} ci
                            ON ci.certificateid = crt.id
                    INNER JOIN {course} c
                            ON c.id = crt.course
                    INNER JOIN {user} u
                            ON u.id=f.userid

                    WHERE   f.userid = ci.userid AND
                            f.userid = :userid AND
                            f.component = 'mod_certificate' AND
                            f.mimetype = 'application/pdf'
                            ORDER BY ci.timecreated DESC";
            // PDF FILES ONLY (f.mimetype = 'application/pdf').

            $certificates = $DB->get_records_sql($sql, array('userid' => $user->userid));

            if ($certificates) {
                $check = true;
                foreach ($certificates as $certdata) {

                    $certdata->printdate = 1; // Modify printdate so that date is always printed.
                    $certdata->printgrade = 1; // Modify printgrade so that grade is always printed.
                    $certdata->gradefmt = 1;
                    // Modify gradefmt so that correct suffix is printed. 1=percentage, 2=points and 3=letter.

                    $certrecord = new stdClass();
                    $certrecord->timecreated = $certdata->citimecreated;

                    // Date format.
                    $dateformat = get_string('strftimedate', 'langconfig');

                    // Required variables for output.
                    $username = $certdata->firstname . ' ' . $certdata->lastname;
                    $userid = $certrecord->userid = $certdata->fuserid;
                    $certificateissueid = $certrecord->certificateissueid = $certdata->ciid;
                    $contextid = $certrecord->contextid = $certdata->ctxid;
                    $courseid = $certrecord->id = $certdata->cid;
                    $coursename = $certrecord->fullname = $certdata->cfullname;
                    $filename = $certrecord->filename = $certdata->ffilename;
                    $certificatename = $certrecord->name = $certdata->crtname;
                    $code = $certrecord->code = $certdata->cicode;

                    // Retrieving grade and date for each certificate.
                    $grade = certificate_get_grade($certdata, $certrecord, $userid, $valueonly = true);
                    $date = $certrecord->timecreated = $certdata->citimecreated;

                    // Linkable Direct course. Use $courselink for clickable course link.
                    $courselink = html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid)), "<strong>" . $coursename . "</strong>", array('fullname' => $coursename)) . "<br>" .
                            "[" . $certificatename . "]";

                    // Non - Linkable course title only. The course link isn't linkable.
                    $link = "<strong>" . $coursename . "</strong>" . "<br>" .
                            "[" . $certificatename . "]";

                    // Direct certificate download link.
                    $filelink = file_encode_url($CFG->wwwroot . '/pluginfile.php', '/'
                            . $contextid . '/mod_certificate/issue/' . $certificateissueid . '/' . $filename);
                    $imglink = html_writer::empty_tag('img', array('src' => new moodle_url(
                                        '/blocks/download_certificates/pix/download.png'), 'alt' => "Please download", 'height' => 40, 'width' => 40));
                    $outputlink = '<a href="' . $filelink . '" >' . $imglink . '</a>';

                    $table->data[] = array($username, $link, $grade, $code, userdate($date, $dateformat), $outputlink);
                }
            }
        }
        if ($check) {
            echo $OUTPUT->heading(get_string('download_certificates_heading', 'block_download_certificates'));
            echo '<br />';

            // Download all previously certificates as Zipped file.
            echo html_writer::table($table);
            $alldownloadslink = $CFG->wwwroot . "/blocks/download_certificates/download_all_my_certificates.php";
            $param = rawurlencode(json_encode($courseids));
            $alldownloads = $CFG->wwwroot . "/blocks/download_certificates/download_all_certificate.php?course=$param";
            echo "<a href='" . $alldownloadslink . "'><button>" . get_string('download_certificates_downloadallcerts', 'block_download_certificates') . "</button></a>";
            echo "<a href='" . $alldownloads . "'><button style='margin-left:1%;'>Download All</button></a>";
        } else {
            echo get_string('nocertsissued', 'block_download_certificates');
        }
    } else {
        echo get_string('nocertsissued', 'block_download_certificates');
    }
}

echo $OUTPUT->footer();
