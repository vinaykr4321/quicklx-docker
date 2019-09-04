<?php
 $dbhost = "localhost";
 $dbuser = "remote";
 $dbpass = "c4r33r4c4d3myr3m0t3";
 $db = "iomad";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 

 /* You can change the time from (1561361858) and to (1561275334)*/
 /*$sql = "SELECT DISTINCT(ggh.userid)
    		FROM
      		`mdl_grade_grades_history` AS ggh
      		JOIN mdl_grade_grades AS gg ON ggh.userid != gg.userid
    		WHERE
      		ggh.timemodified <= 1561361858
		AND ggh.timemodified >= 1554076800";*/
$sql = "SELECT distinct(userid) FROM mdl_grade_grades_history WHERE userid NOT IN (SELECT userid FROM mdl_grade_grades)";


 $user=$conn->query($sql);

 if($user->num_rows > 0)
 {
 	error_log("conn");
	 while($row = $user->fetch_assoc())
	 {
		 $userid = '';
		 $userid = $row["userid"];
		 $sql2="INSERT INTO
  			mdl_grade_grades (
    			id, itemid, userid, rawgrade, rawgrademax, rawgrademin, rawscaleid, usermodified, finalgrade, hidden, locked, locktime, exported, overridden, excluded, feedback, feedbackformat, information, informationformat, timemodified, aggregationweight)
				SELECT
  				X.oldid, X.itemid, X.userid, X.rawgrade, X.rawgrademax, X.rawgrademin, X.rawscaleid, X.usermodified, X.finalgrade, X.hidden, X.locked, X.locktime, X.exported, X.overridden, X.excluded, X.feedback, X.feedbackformat, X.information, X.informationformat, X.timemodified, gi.aggregationcoef2
				FROM(
					SELECT *
					FROM
  					`mdl_grade_grades_history`
					WHERE
  					userid IN (
    						$userid
  					)
					GROUP BY
  					itemid DESC
				) X
				LEFT JOIN `mdl_grade_items` AS gi ON gi.id = X.itemid
				ORDER BY
				gi.sortorder ASC,
				X.id ASC";
		$qresult = $conn->query($sql2);
	}
 } else {
 	error_log("no conn");
 }

 $conn -> close();



?>
