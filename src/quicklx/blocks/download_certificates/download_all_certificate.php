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
require_once("../../config.php");
global $USER, $DB, $CFG;
require_login();

$courseids = json_decode($_GET["course"]);
if ($courseids) {

    $course = $DB->get_records_sql('SELECT u.id as userid,u.firstname,u.username,course.id as course FROM mdl_course AS course 
                                JOIN mdl_enrol AS en ON en.courseid = course.id 
                                JOIN mdl_user_enrolments AS ue ON ue.enrolid = en.id 
                                JOIN mdl_user AS u ON ue.userid = u.id
                                where course.id IN (?)', $courseids);
}
if ($course) {
    $fs = get_file_storage();

    $context = context_system::instance();
    $PAGE->set_context($context);
    mkdir('temp');
// Create a temp file & open it.
    foreach ($course as $user) {
        $zip = new ZipArchive();
        $tmpfile = $user->firstname . '(' . $user->username . ').zip';
        $zip->open($tmpfile, ZipArchive::CREATE);

        $sql = "SELECT f.id AS fid, f.userid AS fuserid, f.contextid AS fcontextid, f.filename AS ffilename,
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

				 WHERE f.userid = ci.userid AND
				       f.userid = :userid AND
				    f.component = 'mod_certificate' AND
                     f.mimetype = 'application/pdf'
		      ORDER BY ci.timecreated DESC";
        // PDF FILES ONLY (f.mimetype = 'application/pdf').

        $tempdirname = "";
        $certificates = $DB->get_records_sql($sql, array('userid' => $user->userid));

        if ($certificates)
            $dirname = "UserID_" . $user->userid . "_certificates_can_delete";
        make_temp_directory($dirname);
        $tempdirname = "$CFG->tempdir/" . $dirname;

        foreach ($certificates as $certdata) {
            $fileinfo = array(
                'component' => 'mod_certificate', // Usually table name.
                'filearea' => 'issue', // Usually table name
                'itemid' => $certdata->ciid, // Usually ID of row in table.
                'contextid' => $certdata->ctxid, // ID of context.
                'filepath' => '/', // Any path beginning and ending in /.
                'filename' => $certdata->ffilename); // Any filename.
            // Get file.
            $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

            // Read contents.
            if ($file) {
                $contents = $file->get_content();
                file_put_contents($tempdirname . "/" . $certdata->ffilename, $contents);
            } else {
                // File doesn't exist - Print error message.
                print_error(get_string('download_certificates_nofilefound', 'block_download_certificates'));
            }
            $zip->addFile($tempdirname . "/" . $certdata->ffilename, $certdata->ffilename);
        }
        $zip->close();
        copy($tmpfile, "temp/$tmpfile");
        rmdir($tempdirname);
        unlink($tmpfile);
    }

    $zipfilename = 'allcertificates.zip';
    Zip('temp', $zipfilename);

    array_map('unlink', glob("temp/*.*"));
    rmdir('temp');

    header("Content-Disposition: attachment; filename=\"" . basename($zipfilename) . "\"");
    header('Content-type: application/zip');
    ob_clean();
    readfile($zipfilename);
    flush();
    unlink($tmpfile);
    unlink($zipfilename);
    exit;
}

function Zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                continue;

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
