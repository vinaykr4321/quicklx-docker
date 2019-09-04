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

require_once('../../config.php');
require_once(dirname('__FILE__') . '/lib.php');
require_once(dirname(__FILE__) . '/../../config.php'); // Creates $PAGE.
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/user/filters/lib.php');
require_once($CFG->dirroot . '/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot . '/report/usersreport/filter_record.php'); // Used for user report filtering.
// We always require users to be logged in for this page.
require_login();

// Get parameters.
$id = optional_param('id', 0, PARAM_INT);
$testscore = optional_param('testscore', 0, PARAM_CLEAN); // Md5 confirmation hash.
$course = optional_param('course', 0, PARAM_INT);
$email = optional_param('email', 0, PARAM_CLEAN);
$lastname = optional_param('lastname', 0, PARAM_CLEAN);
$sort = optional_param('sort', 'name', PARAM_ALPHA);
$dir = optional_param('dir', 'ASC', PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 30, PARAM_INT);
$acl = optional_param('acl', '0', PARAM_INT);
$search = optional_param('search', '', PARAM_CLEAN); // Search string.
$departmentid = optional_param('department', 0, PARAM_INTEGER);
$timestart = optional_param('timestart', 0, PARAM_INTEGER);
$timecreated = optional_param('timecreated', 0, PARAM_INTEGER);
$lastview = optional_param('lastview', 0, PARAM_INTEGER);
$completiondate = optional_param('completiondate', 0, PARAM_INTEGER);
$companyid = optional_param('company', 0, PARAM_INT);
$fullname = optional_param('firstname', 0, PARAM_CLEAN);
$selectoption = optional_param('select', 0, PARAM_CLEAN); // For select per page.

$params = array();
if ($id) {
    $params['id'] = $id;
}
if ($email) {
    $params['email'] = $email;
}
if ($sort) {
    $params['sort'] = $sort;
}
if ($dir) {
    $params['dir'] = $dir;
}
if ($page) {
    $params['page'] = $page;
}
if ($perpage) {
    $params['perpage'] = $perpage;
}
if ($search) {
    $params['search'] = $search;
}
if ($department) {
    // Commented by Syllametrics.
    $params['department'] = $departmentid; // Changed By Syllametrics.
}
if ($course) {
    $params['course'] = $course;
}
if ($testscore) {
    $params['testscore'] = $testscore;
}
if ($timestart) {
    $params['timestart'] = $timestart;
}
if ($lastview) {
    $params['lastview'] = $lastview;
}
if ($completiondate) {
    $params['completiondate'] = $completiondate;
}
if ($fullname) {
    $params['firstname'] = $fullname;
}
if ($lastname) {
    $params['lastname'] = $lastname;
}

$PAGE->requires->js(new moodle_url('/report/usersreport/scripts.js'));

$systemcontext = context_system::instance();

iomad::require_capability('local/report_users:view', $systemcontext);
if ($companyid == 0) {
    if (!is_siteadmin()) {
        $companyid = iomad::get_my_companyid($systemcontext);
    } else {
        $companyid = true;
    }
} else {
    $selectedcompany = $companyid;
}

//$PAGE->requires->js_init_call('select_company',array($companyid), true);
//Select company list

if (!empty($selectedcompany)) {
    $companyname = company::get_companyname_byid($selectedcompany);
} else {
    $companyname = "";
}

// Get a list of companies.
$companylist = company::get_companies_select();
$select = new single_select(new moodle_url('/report/usersreport/index.php'), 'company', $companylist, $selectedcompany);
$select->label = get_string('selectacompany', 'block_iomad_company_selector');
$select->formid = 'choosecompany';
$fwselectoutput = html_writer::tag('div', $OUTPUT->render($select), array(
            'id' => 'iomad_company_selector'
        ));
$fwselectoutput->content->text = $OUTPUT->container_start('companyselect');

//Dropdown for page select.
$selectPage = 0;
$pagelimit = array(
    'option1' => 'Choose...',
    'option2' => '30',
    'option3' => '50',
    'option4' => '100',
    'option5' => '200'
);
if (isset($_GET['select'])) {
    $selectPage = $_GET['select'];
}

if (isset($_GET['firstname'])) {
    $_GET['firstname'] = trim($_GET['firstname']);
}
if (isset($_GET['lastname'])) {
    $_GET['lastname'] = trim($_GET['lastname']);
}
if (isset($_GET['email'])) {
    $_GET['email'] = trim($_GET['email']);
}


$firstname_query1 = "";
$lastname_query1 = "";
$email_query1 = "";


$mform = new userreports($actionQuery);
if ($mform->get_data()) {
    $filterData = $mform->get_data();
    unset($filterData->submitbutton);
    unset($filterData->mform_isexpanded_id_usersearchfields);

    $filterDataArray = (array) $filterData;
    $firstname_query1 = $filterData->firstname;
    $lastname_query1 = $filterData->lastname;
    $email_query1 = $filterData->email;

    foreach ($filterDataArray as $key => $value) {
        if ($value) {
            $filterQuery .= " and u.$key LIKE '%" . $value . "%'";
        }
    }
}




if (!isset($_GET['email'])) {
    $localemail = $_POST['email'];
} else {
    $localemail = $_GET['email'];
}

if (!isset($_GET['firstname'])) {
    $loc1firstname = $_POST['firstname'];
} elseif (isset($_GET['firstname'])) {
    $loc1firstname = $_GET['firstname'];
}


if (!isset($_GET['lastname'])) {
    $loc1lastname = $_POST['lastname'];
} elseif (isset($_GET['lastname'])) {
    $loc1lastname = $_GET['lastname'];
}

if (isset($_GET['company'])) {
    $loc1company = $_GET['company'];
} else {
    $loc1company = 'All';
}


if (isset($_GET['dir'])) {
    $dir = $_GET['dir'];
} else {
    $dir = "";
}

if (isset($_GET['sort'])) {
    $loc1sor = $_GET['sort'];
} else {
    $loc1sor = "";
}


$loc1page = $_GET['0'];

switch ($_GET['select']) {
    case 'option2':
        $loc1perpage = '30';
        break;
    case 'option3':
        $loc1perpage = '50';
        break;
    case 'option4':
        $loc1perpage = '100';
        break;
    case 'option5':
        $loc1perpage = '200';
        break;
    default:
        $loc1perpage = '30';
        break;
}

$url1 = "index.php?sort=" . $loc1sor . "&dir=" . $dir . "&perpage=" . $loc1perpage . "&company=" . $loc1company . "&lastname=" . $loc1lastname . "&firstname=" . $loc1firstname . "&email=" . $localemail . "&page=" . $loc1page;

$pageselect = new single_select(new moodle_url($url1), 'select', $pagelimit, "$selectPage", '');
$pageselect->label = 'Show per page';
$pageselect->formid = 'showperpage';
$ppselectoutput = html_writer::tag('div', $OUTPUT->render($pageselect), array(
            'id' => 'selectperpage'
        ));


if (!empty($SESSION->currenteditingcompany)) {
    $fwselectoutput->content->text .= '<p>' . get_string('currentcompanyname', 'block_iomad_company_selector', $companyname) . '</p>';
} else {
    $fwselectoutput->content->text .= '<p>' . get_string('nocurrentcompany', 'block_iomad_company_selector') . '</p>';
}
$fwselectoutput->content->text .= $fwselectoutput;
$fwselectoutput->content->text .= $OUTPUT->container_end();
// Correct the navbar.
// Set the name for the page.
$linktext = get_string('report_users_title', 'report_usersreport');
// Set the url.
//$linkurl = new moodle_url('/report/usersreport/index.php');
// Print the page header.
$PAGE->set_context($systemcontext);
//$PAGE->set_url($linkurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($linktext);
// get output renderer                                                                                                                                                                                         
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Javascript for fancy select.
// Parameter is name of proper select form element followed by 1=submit its form
$PAGE->requires->js_call_amd('block_iomad_company_admin/department_select', 'init', array(
    'departmentid',
    1,
    optional_param('departmentid', 0, PARAM_INT)
));

// Set the page heading.
$PAGE->set_heading(get_string('pluginname', 'report_usersreport') . " - $linktext");
// Get the renderer.
//$output = $PAGE->get_renderer('block_iomad_company_admin');
// Build the nav bar.
//company_admin_fix_breadcrumb($PAGE, $linktext, $linkurl);

echo $output->header();

//Select list of companies. 
if (is_siteadmin()) {
    echo $fwselectoutput;
    //echo $ppselectoutput;
}

// Get the associated department id.
$company = new company($companyid);
$parentlevel = company::get_company_parentnode($company->id);
$companydepartment = $parentlevel->id;

// Get the company additional optional user parameter names.
$fieldnames = array();
if ($category = company::get_category($companyid)) {
    // Get field names from company category.
    if ($fields = $DB->get_records('user_info_field', array(
        'categoryid' => $category->id
            ))) {
        foreach ($fields as $field) {
            $fieldnames[$field->id] = 'profile_field_' . $field->shortname;
            ${'profile_field_' . $field->shortname} = optional_param('profile_field_' . $field->shortname, null, PARAM_RAW);
        }
    }
}

// Deal with the user optional profile search.
$urlparams = $params;
$idlist = array();
$foundfields = false;
if (!empty($fieldnames)) {
    $fieldids = array();
    foreach ($fieldnames as $id => $fieldname) {
        $paramarray = array();
        if ($fields[$id]->datatype == "menu") {
            $paramarray = explode("\n", $fields[$id]->param1);
            if (!empty($paramarray[${$fieldname}])) {
                ${$fieldname} = $paramarray[${$fieldname}];
            }
        }
        if (!empty(${$fieldname})) {
            $idlist[0] = "We found no one";
            $fieldsql = $DB->sql_compare_text('data') . " LIKE '%" . ${$fieldname} . "%'
                                                        AND fieldid = $id";
            if ($idfields = $DB->get_records_sql("SELECT userid FROM {user_info_data}
                                                  WHERE $fieldsql")) {
                $fieldids[] = $idfields;
            }
            if (!empty($paramarray)) {
                $params[$fieldname] = array_search(${$fieldname}, $paramarray);
                $urlparams[$fieldname] = array_search(${$fieldname}, $paramarray);
            } else {
                if (!is_array(${$fieldname})) {
                    $params[$fieldname] = ${$fieldname};
                    $urlparams[$fieldname] = ${$fieldname};
                } else {
                    $params[$fieldname] = ${$fieldname};
                    $urlparams[$fieldname] = serialize(${$fieldname});
                }
            }
        }
    }
    if (!empty($fieldids)) {
        $foundfields = true;
        $idlist = array_pop($fieldids);
        if (!empty($fieldids)) {
            foreach ($fieldids as $fieldid) {
                $idlist = array_intersect_key($idlist, $fieldid);
                if (empty($idlist)) {
                    break;
                }
            }
        }
    }
}
// Deal with the user optional profile search.
$urlparams = $params;

if (iomad::has_capability('block/iomad_company_admin:edit_all_departments', $systemcontext) || !empty($SESSION->currenteditingcompany)) {
    $userhierarchylevel = $parentlevel->id;
} else {
    $userlevel = $company->get_userlevel($USER);
    $userhierarchylevel = $userlevel->id;
}
if ($departmentid == 0) {
    $departmentid = $userhierarchylevel;
}

// Carry on with the user listing.
$columns = array(
    "id",
    "firstname",
    "email",
    "course",
    "timestart",
    "lastview",
    "department",
    "testscore",
    "completiondate"
);

foreach ($columns as $column) {
    if ($column == 'email') {
        $string[$column] = get_string("$column", 'local_report_completion');
    } else {
        $string[$column] = get_string("$column");
    }
    if ($sort != $column) {
        $columnicon = "";
        if ($column == "timestart") {
            $columndir = "DESC";
        } else {
            $columndir = "ASC";
        }
        if ($column == "lastview") {
            $columndir = "DESC";
        } else {
            $columndir = "ASC";
        }
    } else {
        $columndir = $dir == "ASC" ? "DESC" : "ASC";
        if ($column == "timestart") {
            $columnicon = $dir == "ASC" ? "up" : "down";
        } else {
            $columnicon = $dir == "ASC" ? "down" : "up";
        }
        if ($column == "lastview") {
            $columnicon = $dir == "ASC" ? "up" : "down";
        } else {
            $columnicon = $dir == "DESC" ? "down" : "up";
        }
        $columnicon = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    }
    $params['sort'] = $column;
    $params['dir'] = $columndir;
    $$column = "<a href= " . new moodle_url('index.php', $params) . ">" . $string[$column] . "</a>$columnicon";
    $$column = $string[$column] . $columnicon;
}
if ($sort == "name") {
    $sort = "email";
}
// Get the full company tree as we may need it.
$topcompanyid = $company->get_topcompanyid();
$topcompany = new company($topcompanyid);
$companytree = $topcompany->get_child_companies_recursive();
$parentcompanies = $company->get_parent_companies_recursive();

// Deal with parent company managers
if (!empty($parentcompanies)) {
    $userfilter = " AND id NOT IN (
                     SELECT userid FROM {company_users}
                     WHERE companyid IN (" . implode(',', array_keys($parentcompanies)) . "))";
    $userfilterwithu = " AND u.id NOT IN (
                         SELECT userid FROM {company_users}
                         WHERE companyid IN (" . implode(',', array_keys($parentcompanies)) . "))";
} else {
    $userfilter = "";
    $userfilterwithu = "";
}


// Get all or company users depending on capability.
$dbsort = "";

// Check if has capability edit all users.
//if (iomad::has_capability('block/iomad_company_admin:editallusers', $systemcontext)) {
// Check we havent looked and discounted everyone.
if ((empty($idlist) && !$foundfields) || (!empty($idlist) && $foundfields)) {
    // Make sure we dont display site admins.
    // Set default search to something which cant happen.
    $sqlsearch = "id!='-1' AND id NOT IN (" . $CFG->siteadmins . ") $userfilter";

    // Get department users.
    $departmentusers = company::get_recursive_department_users($departmentid);
    if (count($departmentusers) > 0) {
        $departmentids = "";
        foreach ($departmentusers as $departmentuser) {
            if (!empty($departmentids)) {
                $departmentids .= "," . $departmentuser->userid;
            } else {
                $departmentids .= $departmentuser->userid;
            }
        }
        if (!empty($showsuspended)) {
            $sqlsearch .= " AND deleted <> 1 AND id in ($departmentids) ";
        } else {
            $sqlsearch .= " AND deleted <> 1 AND suspended = 0 AND id in ($departmentids) ";
        }
    } else {
        $sqlsearch = "1 = 0";
    }

    // Get the user records.
    $userrecords = $DB->get_fieldset_select('user', 'id', $sqlsearch);
} else {
    $userrecords = array();
}

// Check we havent looked and discounted everyone.
if ((empty($idlist) && !$foundfields) || (!empty($idlist) && $foundfields)) {
    // Get users company association.
    $departmentusers = company::get_recursive_department_users($departmentid);
    $sqlsearch = "id!='-1' $userfilter";
    if (count($departmentusers) > 0) {
        $departmentids = "";
        foreach ($departmentusers as $departmentuser) {
            if (!empty($departmentids)) {
                $departmentids .= "," . $departmentuser->userid;
            } else {
                $departmentids .= $departmentuser->userid;
            }
        }
        if (!empty($showsuspended)) {
            $sqlsearch .= " AND deleted <> 1 AND id in ($departmentids) ";
        } else {
            $sqlsearch .= " AND deleted <> 1 AND suspended = 0 AND id in ($departmentids) ";
        }
    } else {
        $sqlsearch = "1 = 0";
    }
    // Deal with search strings.
    $searchparams = array();
    if (!empty($idlist)) {
        $sqlsearch .= " AND id in (" . implode(',', array_keys($idlist)) . ") ";
    }
    if (!empty($params['course'])) {
        $sqlsearch .= " AND course like :course ";
        $searchparams['course'] = '%' . $params['course'] . '%';
    }

    if (!empty($params['department'])) {
        $sqlsearch .= " AND department like :department ";
        $searchparams['department'] = '%' . $params['department'] . '%';
    }

    if (!empty($params['email'])) {
        $sqlsearch .= " AND email like :email ";
        $searchparams['email'] = '%' . $params['email'] . '%';
    }
    if (!empty($params['lastname'])) {
        $sqlsearch .= " AND lastname like :lastname ";
        $searchparams['lastname'] = '%' . $params['lastname'] . '%';
    }
    if (!empty($params['timestart'])) {
        $sqlsearch .= " AND timestart like :timestart ";
        $searchparams['timestart'] = '%' . $params['timestart'] . '%';
    }
    switch ($sort) {
        case "id":
            $sqlsearch .= " order by id $dir ";
            $dbsort = " order by u.id $dir ";
            break;
        case "email":
            $sqlsearch .= " order by email $dir ";
            $dbsort = " order by u.email $dir ";
            break;
        case "lastname":
            $sqlsearch .= " order by lastname $dir ";
            $dbsort = " order by u.lastname $dir ";
            break;
        case "course":
            $dbsort = " order by c.fullname $dir ";
            break;
        case "timestart":
            $dbsort = " order by ue.timestart $dir";
            break;
        case "department":
            $dbsort = " order by u.department $dir ";
            break;
        case "lastview":
            $dbsort = "ORDER BY ul.timeaccess $dir";
            break;
        case "finalgrade":
            $dbsort = "ORDER BY gg.finalgrade $dir";
            break;
        case "completiondate":
            $dbsort = "ORDER BY st.timemodified $dir";
            break;
        case "firstname":
            $dbsort = "ORDER BY u.firstname $dir";
            break;
    }


    if (isset($_GET['select'])) {
        if ($_GET['select'] == 'option2') {
            $perpage = "30";
        } elseif ($_GET['select'] == 'option3') {
            $perpage = "50";
        } elseif ($_GET['select'] == 'option4') {
            $perpage = "100";
        } elseif ($_GET['select'] == 'option5') {
            $perpage = "200";
        }
    }

    if ($_GET['company'] == "All" || (!$_GET['company'])) {
        $userrecords = array_keys($DB->get_records_sql("SELECT id FROM {company_users}"));
    } else {
        //$userrecords = $DB->get_fieldset_select('user', 'id', $sqlsearch . $userfilter); // Commented By Syllametrics
        $userrecords = $DB->get_records_sql("SELECT id FROM {company_users} WHERE companyid = $companyid"); // Changed By Syllametrics
    }
    // $userrecords = $DB->get_fieldset_select('user', 'id', $sqlsearch . $userfilter);
} else {
    $userrecords = array();
}

//}


/* Userreports Filtering Process Added filter.php included  */

$filterQuery = "";

if ($_GET['company'] != "All" && ($_GET['company'] != "")) {
    $actionQuery = 'index.php?company=' . $companyid;
}

$firstname_query = "";
$lastname_query = "";
$email_query = "";


$mform = new userreports($actionQuery);
if ($mform->get_data()) {
    $filterData = $mform->get_data();

    unset($filterData->submitbutton);
    unset($filterData->mform_isexpanded_id_usersearchfields);

    $filterDataArray = (array) $filterData;
    $firstname_query = $filterData->firstname;
    $lastname_query = $filterData->lastname;
    $email_query = $filterData->email;

    foreach ($filterDataArray as $key => $value) {
        if ($value) {
            $filterQuery .= " and u.$key LIKE '%" . trim($value) . "%'";
        }
    }
}
$mform->display();

function getrecords($searchparams, $companyids, $dbsort = "", $filterQuery = "", $page = "", $perpage = "", $companyid = "") {
    $locLastname = $_GET["lastname"];
    $locFirstname = $_GET["firstname"];
    $locEmail = $_GET["email"];


    global $DB;

    $PageQuery = "";
    if ($perpage) {
        $PageQuery = $page * $perpage . ',' . $perpage;
    }



    $filterQuery .= " and u.firstname LIKE '%" . $_GET["firstname"] . "%'";
    $filterQuery .= " and u.email LIKE '%" . $_GET["email"] . "%'";
    $filterQuery .= " and u.lastname LIKE '%" . $_GET["lastname"] . "%'";
    if ($companyid == 22) {
        return $DB->get_records_sql("SELECT @s:=@s+1 n,u.id as id,u.idnumber as idnumber,
                                    CONCAT(u.firstname, ' ', u.lastname) AS 'fullname',
                                    u.email as email, u.lastname as lastname,
                                    u.city AS 'Department',
                                    c.fullname AS 'Course',
                                    c.id AS 'courseid', 
                                    ROUND(gg.finalgrade / gg.rawgrademax * 100 ,2) AS 'TestScore',
                                    ue.timestart AS 'TimeStart',
                                    ul.timeaccess AS 'LastView',
                                    st.timemodified AS 'CompletionDate'
                                    FROM {course} AS c
                                    JOIN {course_categories} AS cc ON cc.id = c.category
                                    JOIN {context} AS ctx ON c.id = ctx.instanceid
                                    JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
                                    JOIN {user} AS u ON u.id = ra.userid
                                    JOIN {company_users} AS comp_u ON comp_u.userid = u.id
                                    JOIN {grade_items} AS gi ON gi.courseid = c.id
                                    JOIN {scorm} AS s ON c.id = s.course
                                    JOIN {user_enrolments} AS ue ON ue.userid = u.id
                                    LEFT JOIN {user_lastaccess} AS ul ON ul.userid = u.id
                                    LEFT JOIN {grade_grades} AS gg ON gi.id = gg.itemid AND gg.userid = u.id
                                    LEFT JOIN {scorm_scoes_track} AS st ON gg.userid = st.userid AND st.value ='passed' AND gg.rawgrade IS NOT NULL
                                    LEFT JOIN {course_completions} AS p ON p.userid = u.id AND p.course = c.id
                                    LEFT JOIN (
                                        SELECT asgm.status as status, c.id as cid, u.id as uid,  gi.id as giid
                                        FROM {course} AS c
                                        JOIN {course_categories} AS cc ON cc.id = c.category
                                        JOIN {context} AS ctx ON c.id = ctx.instanceid
                                        JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
                                        JOIN {user} AS u ON u.id = ra.userid
                                        JOIN {grade_items} AS gi ON gi.courseid = c.id
                                        JOIN {assign} AS asg ON gi.iteminstance = asg.id and asg.course = c.id
                                        JOIN {assign_submission} AS asgm ON asg.id = asgm.assignment and asgm.userid = u.id 
                                                ) AS asgm ON c.id = asgm.cid and u.id = asgm.uid  and gi.id = asgm.giid
                                    ,(SELECT @s:=0) n 
                                    WHERE (gi.itemmodule = 'quiz' OR gi.itemmodule= 'assign' OR gi.itemmodule= 'scorm') AND c.visible=1 $companyids $filterQuery GROUP BY u.id,c.fullname,c.id,TestScore $dbsort ", $searchparams, $page * $perpage, $perpage);
    } else {
        return $DB->get_records_sql("SELECT @s:=@s+1 n,u.id as id,u.idnumber as idnumber,
                                    CONCAT(u.firstname, ' ', u.lastname) AS 'fullname',
                                    u.email as email, u.lastname as lastname,
                                    u.department AS 'Department',
                                    c.fullname AS 'Course',
                                    c.id AS 'courseid', 
                                    ROUND(gg.finalgrade / gg.rawgrademax * 100 ,2) AS 'TestScore',
                                    ue.timestart AS 'TimeStart',
                                    ul.timeaccess AS 'LastView',
                                    st.timemodified AS 'CompletionDate'
                                    FROM {course} AS c
                                    JOIN {course_categories} AS cc ON cc.id = c.category
                                    JOIN {context} AS ctx ON c.id = ctx.instanceid
                                    JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
                                    JOIN {user} AS u ON u.id = ra.userid
                                    JOIN {company_users} AS comp_u ON comp_u.userid = u.id
                                    JOIN {grade_items} AS gi ON gi.courseid = c.id
                                    JOIN {scorm} AS s ON c.id = s.course
                                    JOIN {user_enrolments} AS ue ON ue.userid = u.id
                                    LEFT JOIN {user_lastaccess} AS ul ON ul.userid = u.id
                                    LEFT JOIN {grade_grades} AS gg ON gi.id = gg.itemid AND gg.userid = u.id
                                    LEFT JOIN {scorm_scoes_track} AS st ON gg.userid = st.userid AND st.value ='passed' AND gg.rawgrade IS NOT NULL
                                    LEFT JOIN {course_completions} AS p ON p.userid = u.id AND p.course = c.id
                                    LEFT JOIN (
                                        SELECT asgm.status as status, c.id as cid, u.id as uid,  gi.id as giid
                                        FROM {course} AS c
                                        JOIN {course_categories} AS cc ON cc.id = c.category
                                        JOIN {context} AS ctx ON c.id = ctx.instanceid
                                        JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
                                        JOIN {user} AS u ON u.id = ra.userid
                                        JOIN {grade_items} AS gi ON gi.courseid = c.id
                                        JOIN {assign} AS asg ON gi.iteminstance = asg.id and asg.course = c.id
                                        JOIN {assign_submission} AS asgm ON asg.id = asgm.assignment and asgm.userid = u.id 
                                                ) AS asgm ON c.id = asgm.cid and u.id = asgm.uid  and gi.id = asgm.giid
                                    ,(SELECT @s:=0) n 
                                    WHERE (gi.itemmodule = 'quiz' OR gi.itemmodule= 'assign' OR gi.itemmodule= 'scorm') AND c.visible=1 $companyids $filterQuery GROUP BY u.id,c.fullname,c.id,TestScore $dbsort ", $searchparams, $page * $perpage, $perpage);
    }
}

//$DB->set_debug(true);

$userlist = "";
if (!empty($userrecords)) {
    //$userlist = " u.id in (". implode(',', array_values($userrecords)).") "; // Commented By Syllametrics
    $userlist = " u.id IN (" . implode(',', array_keys($userrecords)) . ") "; // Changed by Syllametrics
}

if ($_GET['company'] == "All" || (!$_GET['company'])) {
    $companyids = "";
} else {
    $companyids = "AND comp_u.companyid = $companyid";
}
if (!empty($userlist)) {
    //$DB->set_debug(true);
    $users = getrecords($searchparams, $companyids, $dbsort, $filterQuery, $page, $perpage, $companyid);
} else {
    $users = array();
}
$usercount = count(getrecords($searchparams, $companyids, $dbsort, $filterQuery));

// Fix sort for paging.
$params['sort'] = $sort;
$params['dir'] = $dir;

if (isset($_GET['firstname'])) {
    $firstname_query = $_GET['firstname'];
}
if (isset($_GET['lastname'])) {
    $lastname_query = $_GET['lastname'];
}
if (isset($_GET['email'])) {
    $email_query = $_GET['email'];
}




if ($_GET['company'] == "All" || (!$_GET['company']) && $selectoption != 0) {
    $baseurl = new moodle_url('/report/usersreport/index.php', array(
        'sort' => $sort,
        'dir' => $dir,
        'perpage' => $perpage,
        'company' => 'All',
        'select' => $_GET['select'],
        'lastname' => $lastname_query,
        'firstname' => $firstname_query,
        'email' => $email_query
    ));
} elseif ($_GET['company'] == "All" || (!$_GET['company'])) {
    $baseurl = new moodle_url('/report/usersreport/index.php', array(
        'sort' => $sort,
        'dir' => $dir,
        'perpage' => $perpage,
        'company' => 'All',
        'lastname' => $lastname_query,
        'firstname' => $firstname_query,
        'email' => $email_query
    ));
} elseif ($selectoption != 0) {
    $params['company'] = $companyid;
    $baseurl = new moodle_url('/report/usersreport/index.php', array(
        'sort' => $sort,
        'dir' => $dir,
        'perpage' => $perpage,
        'company' => $companyid,
        'select' => $_GET['select']
    ));
} else {
    $params['company'] = $companyid;
    $baseurl = new moodle_url('/report/usersreport/index.php', array(
        'sort' => $sort,
        'dir' => $dir,
        'perpage' => $perpage,
        'company' => $companyid
    ));
}


echo $ppselectoutput;


echo $OUTPUT->paging_bar($usercount, $page, $perpage, $baseurl);
flush();

if (!$users) {
    $match = array();
    echo $output->heading(get_string('nousersfound'));

    echo "<p><a class='btn' href='" . new moodle_url('/blocks/iomad_company_admin/company_user_create_form.php') . "'>" . get_string('createuser', 'block_iomad_company_admin') . "</a></p>";

    $table = null;
} else {

    $countries = get_string_manager()->get_list_of_countries();


    /* Start Excel Download Data  */


    $excelusers = getrecords($searchparams, $companyids, $dbsort, $filterQuery, '', '', $companyid);
    $array_excel = array();
    $array_excel[] = array(
        'Id',
        'Full Name',
        'Email',
        'Course',
        'First view',
        'Last view',
        'Department',
        'Test score',
        'Completion date'
    );

    foreach ($excelusers as $key => $exceluser) {

        if (!$exceluser->idnumber) {
            $exceluser->idnumber = $exceluser->id;
        }

        if (!$exceluser->timestart) {
            $exceluser->timestart = "";
        }

        if (!$exceluser->lastview) {
            $exceluser->lastview = "";
        }

        if (!$exceluser->completiondate) {
            $exceluser->completiondate = "";
        }



        $array_excel[] = array(
            $exceluser->idnumber,
            $exceluser->fullname,
            $exceluser->email,
            $exceluser->course,
            date("m/d/Y", $exceluser->timestart),
            date("m/d/Y", $exceluser->lastview),
            $exceluser->department,
            $exceluser->testscore,
            date("m/d/Y", $exceluser->completiondate)
        );
    }


    /* End Excel Download Data  */


    foreach ($users as $key => $user) {
        if (!empty($user->country)) {
            $users[$key]->country = $countries[$user->country];
        }
    }

    $mainadmin = get_admin();
    // Set the initial parameters for the table header links.
    $linkparams = $urlparams;
    // Set the defaults.
    $linkparams['dir'] = 'DESC';
    $linkparams['sort'] = 'id';
    $idurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'course';
    $courseurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'department';
    $departmenturl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'email';
    $emailurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'lastname';
    $lastnameurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'finalgrade';
    $testscoreurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'timestart';
    $timestarturl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'lastview';
    $lastviewurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'completiondate';
    $completiondateurl = new moodle_url('index.php', $linkparams);
    $linkparams['sort'] = 'firstname';
    $firstnameurl = new moodle_url('index.php', $linkparams);
    // Set the options if there is alread a sort.
    if (!empty($params['sort'])) {
        if ($params['sort'] == 'id') {
            $linkparams['sort'] = 'id';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $idurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $idurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'email') {
            $linkparams['sort'] = 'email';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $emailurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $emailurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'course') {
            $linkparams['sort'] = 'course';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $courseurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $courseurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'timestart') {
            $linkparams['sort'] = 'timestart';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $timestarturl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $timestarturl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'lastview') {
            $linkparams['sort'] = 'lastview';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $lastviewurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $lastviewurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'department') {
            $linkparams['sort'] = 'department';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $departmenturl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $departmenturl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'finalgrade') {
            $linkparams['sort'] = 'finalgrade';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $testscoreurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $testscoreurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'completiondate') {
            $linkparams['sort'] = 'completiondate';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $completiondateurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $completiondateurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'firstname') {
            $linkparams['sort'] = 'firstname';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $firstnameurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $firstnameurl = new moodle_url('index.php', $linkparams);
            }
        } else if ($params['sort'] == 'lastname') {
            $linkparams['sort'] = 'lastname';
            if ($params['dir'] == 'ASC') {
                $linkparams['dir'] = 'DESC';
                $lastnameurl = new moodle_url('index.php', $linkparams);
            } else {
                $linkparams['dir'] = 'ASC';
                $lastnameurl = new moodle_url('index.php', $linkparams);
            }
        }
    }




    // Changed By Syllametrics
    if ($_GET['company'] == "All" || (!$_GET['company']) && $selectoption != 0) {
        $idurl = $idurl . "&company=All&select=$selectoption";
        $emailurl = $emailurl . "&company=All&select=$selectoption";
        $lastnameurl = $lastnameurl . "&company=All&select=$selectoption";
        $courseurl = $courseurl . "&company=All&select=$selectoption";
        $timestarturl = $timestarturl . "&company=All&select=$selectoption";
        $lastviewurl = $lastviewurl . "&company=All&select=$selectoption";
        $departmenturl = $departmenturl . "&company=All&select=$selectoption";
        $testscoreurl = $testscoreurl . "&company=All&select=$selectoption";
        $completiondateurl = $completiondateurl . "&company=All&select=$selectoption";
        $firstnameurl = $firstnameurl . "&company=All&select=$selectoption";
    } elseif ($_GET['company'] == "All" || (!$_GET['company'])) {
        $idurl = $idurl . "&company=All";
        $emailurl = $emailurl . "&company=All";
        $lastnameurl = $lastnameurl . "&company=All";
        $courseurl = $courseurl . "&company=All";
        $timestarturl = $timestarturl . "&company=All";
        $lastviewurl = $lastviewurl . "&company=All";
        $departmenturl = $departmenturl . "&company=All";
        $testscoreurl = $testscoreurl . "&company=All";
        $completiondateurl = $completiondateurl . "&company=All";
        $firstnameurl = $firstnameurl . "&company=All";
    } elseif ($companyid != 0) {
        $idurl = $idurl . "&company=$companyid";
        $emailurl = $emailurl . "&company=$companyid";
        $lastnameurl = $lastnameurl . "&company=$companyid";
        $courseurl = $courseurl . "&company=$companyid";
        $timestarturl = $timestarturl . "&company=$companyid";
        $lastviewurl = $lastviewurl . "&company=$companyid";
        $departmenturl = $departmenturl . "&company=$companyid";
        $testscoreurl = $testscoreurl . "&company=$companyid";
        $completiondateurl = $completiondateurl . "&company=$companyid";
        $firstnameurl = $firstnameurl . "&company=$companyid";
    }



    if ($_GET['dir'] == "ASC" && $_GET['sort'] == "id") {
        $columnicon = "up";
        $columniconid = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "id") {
        $columnicon = "down";
        $columniconid = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "ASC" && $_GET['sort'] == "firstname") {
        $columnicon = "up";
        $columniconfn = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "firstname") {
        $columnicon = "down";
        $columniconfn = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "ASC" && $_GET['sort'] == "course") {
        $columnicon = "up";
        $columniconc = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "course") {
        $columnicon = "down";
        $columniconc = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "ASC" && $_GET['sort'] == "timestart") {
        $columnicon = "up";
        $columniconts = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "timestart") {
        $columnicon = "down";
        $columniconts = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "ASC" && $_GET['sort'] == "lastview") {
        $columnicon = "up";
        $columniconlv = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "lastview") {
        $columnicon = "down";
        $columniconlv = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "ASC" && $_GET['sort'] == "finalgrade") {
        $columnicon = "up";
        $columniconfg = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "finalgrade") {
        $columnicon = "down";
        $columniconfg = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "ASC" && $_GET['sort'] == "completiondate") {
        $columnicon = "up";
        $columniconcd = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    } elseif ($_GET['dir'] == "DESC" && $_GET['sort'] == "completiondate") {
        $columnicon = "down";
        $columniconcd = " <img src=\"" . $output->image_url('t/' . $columnicon) . "\" alt=\"\" />";
    }




    $table = new html_table();
    $headstart = array(
        $id => $output->action_link($idurl, get_string('id', 'report_usersreport') . $columniconid),
        $fullname => $output->action_link($firstnameurl, get_string('fullname', 'report_usersreport') . $columniconfn),
        $email => $output->action_link($emailurl, $email),
        $course => $output->action_link($courseurl, get_string('coursename', 'report_usersreport') . $columniconc)
    );
    $headmid = array(
        $timestart => $output->action_link($timestarturl, get_string('firstview', 'report_usersreport') . $columniconts),
        $lastview => $output->action_link($lastviewurl, get_string('lastview', 'report_usersreport') . $columniconlv),
        $department => $output->action_link($departmenturl, $department)
    );

    $headend = array(
        $testscore => $output->action_link($testscoreurl, get_string('testscore', 'report_usersreport') . $columniconfg),
        $completiondate => $output->action_link($completiondateurl, get_string('completiondate', 'report_usersreport') . $columniconcd)
    );
    $table->head = $headstart + $headmid + $headend;
    $table->align = array(
        "left",
        "left",
        "left",
        "left",
        "left",
        "left",
        "center",
        "center",
        "center"
    );
    $table->width = "95%";

    foreach ($users as $user) {
        // load the full user profile.
        profile_load_data($user);

        $userurl = "../../local/report_users/userdisplay.php";
        if (!empty($user->idnumber) && is_numeric($user->idnumber)) {
            $rowstart = array(
                'id' => $user->idnumber,
                'fullname' => "<a style='text-decoration:none' href='" . new moodle_url($userurl, array(
            'userid' => $user->id,
            'courseid' => $user->courseid
                )) . "'>" . strtoupper($user->fullname) . "</a>",
                'email' => strtoupper($user->email),
                'department' => $user->course
            );
        } else {
            $rowstart = array(
                'id' => $user->id,
                'fullname' => "<a style='text-decoration:none' href='" . new moodle_url($userurl, array(
            'userid' => $user->id,
            'courseid' => $user->courseid
                )) . "'>" . strtoupper($user->fullname) . "</a>",
                'email' => strtoupper($user->email),
                'department' => $user->course
            );
        }
        if ($user->lastview == NULL) {
            $userlastview = "";
        } else {
            $userlastview = $user->lastview;
        }
        if ($user->timestart == NULL) {
            $userfirstview = "";
        } else {
            $userfirstview = $user->timestart;
        }
        if ($user->completiondate == NULL) {
            $datecompletion = "";
        } else {
            $datecompletion = $user->completiondate;
        }
        $rowmid = array(
            'timestart' => date("m/d/Y", $userfirstview),
            'lastview' => date("m/d/Y", $userlastview),
            'course' => $user->department
        );

        $rowend = array(
            'coursename' => $user->testscore,
            'completiondate' => date("m/d/Y", $datecompletion)
        );
        $table->data[] = $rowstart + $rowmid + $rowend;
    }
}



/*  Download Excel Section */

$PAGE->set_url('/report/usersreport/download_excel.php');
echo $OUTPUT->single_button(new moodle_url($PAGE->url, array(
    'exceldata' => json_encode($array_excel)
        )), 'Export Data');


if (!empty($table)) {
    echo html_writer::start_tag('div', array(
        'class' => 'no-overflow',
        'style' => 'margin-top:10px;'
    ));
    echo html_writer::table($table);
    echo html_writer::end_tag('div');
    echo $OUTPUT->paging_bar($usercount, $page, $perpage, $baseurl);
}


$PAGE->set_url('/report/usersreport/download_excel.php');
echo "<br>" . $OUTPUT->single_button(new moodle_url($PAGE->url, array(
    'exceldata' => json_encode($array_excel)
        )), 'Export Data');
echo '<script>$(".singlebutton").css("margin-bottom","15px")</script>';

echo $output->footer();