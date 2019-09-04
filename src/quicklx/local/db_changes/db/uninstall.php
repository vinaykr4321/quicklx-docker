<?php

function xmldb_local_db_changes_uninstall() {

    global $DB;

    $DB->execute("DROP TABLE {companylicense_permittedcompanies}");

    $DB->execute("ALTER TABLE {company} DROP COLUMN unlicensed, DROP COLUMN unassigned, DROP COLUMN splashimage, DROP COLUMN leftcolumncolor, DROP COLUMN loginbackground, DROP COLUMN loginbutton, DROP COLUMN expiredcourse");

    $DB->execute("ALTER TABLE {companylicense} DROP COLUMN licenseformatname, DROP COLUMN licenseformat, DROP COLUMN sharingtypes, DROP COLUMN parentlicenseid, DROP COLUMN unlimitedseats, DROP COLUMN removetags, DROP COLUMN removecourse");

    $DB->execute("ALTER TABLE {companylicense_courses} DROP COLUMN tag"); 
}
