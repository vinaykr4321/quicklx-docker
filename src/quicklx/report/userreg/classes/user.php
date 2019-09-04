<?php
//require_once('../../config.php');
class user
{
	function user_registration($orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage)
	{

		$limit = '';
		$companyidParam ='';
		if (!empty($orderbyName)) 
		{
			if ($orderbyName == 'name') 
			{
				$orderbyParam = ' c.'.$orderbyName.' '.$sortingOrder;
			}
			else
			{
				$orderbyParam = ' u.'.$orderbyName.' '.$sortingOrder;
			}
		}
		else
		{
			$orderbyParam = 'u.id ASC';
		}
		
		if (!empty($companyid) && $companyid !=0) 
		{
			$companyidParam = ' where c.id = '.$companyid;
		}
/*
		if (!empty($selectPerPage) && $selectPerPage !=0 ) 
		{
			$pagelimit = array('1'=>'30','2'=>'50','3'=>'100','4'=>'200');
			$limit = 'limit '.$pagelimit[$selectPerPage];
		}*/
		if ($selectPerPage !=0) 
		{
			$pagelimit = array('1'=>'30','2'=>'50','3'=>'100','4'=>'200');
			$from  = $page * $pagelimit[$selectPerPage];
			$to  = $pagelimit[$selectPerPage];
			$limit = 'limit '.$from.','.$to;
		}
		else
		{
			$from  = $page * $perpage;
			$to  = $perpage;
			$limit = 'limit '.$from.','.$to;
		}

		global $DB;
		$data = $DB->get_records_sql("SELECT u.id,u.username,u.firstname,u.lastname,c.name,u.timecreated,u.firstaccess,u.lastaccess,u.email FROM {user} AS u LEFT JOIN {company_users} AS cu ON cu.userid=u.id LEFT JOIN {company} AS c ON c.id=cu.companyid $companyidParam ORDER BY $orderbyParam $limit ");
		return $data;
	}


	function user_registration_with_param($firstname,$lastname,$email,$orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage,$firstaccessfrom,$firstaccessto,$lastaccessfrom,$lastaccessto)
	{
		$param = '';
		$dateFilter = '';
		//$limit = '';
		if (!empty($orderbyName)) 
		{
			$orderbyParam = $orderbyName.' '.$sortingOrder;
		}
		else
		{
			$orderbyParam = 'u.id ASC';
		}

		if (!empty($firstname) || !empty($lastname) || !empty($email)) 
		{
			$param = ' where ';
		}

		if (!empty($firstname)) 
		{
			$param .= " u.firstname LIKE '%$firstname%' ";
		}

		if (empty($firstname) && !empty($lastname)) 
		{
			$param .= " u.lastname LIKE '%$lastname%' ";
		}
		elseif (!empty($firstname) && !empty($lastname)) 
		{
			$param .= " AND u.lastname LIKE '%$lastname%' ";
		}

		if (empty($firstname) && empty($lastname) && !empty($email)) 
		{
			$param .= " u.email LIKE '%$email%' ";
		}
		elseif ((!empty($firstname) || !empty($lastname)) && !empty($email)) 
		{
			$param .= " AND u.email LIKE '%$email%' ";
		}

		if ($companyid == 0) 
		{
			$companyid = '';
		}
		elseif(empty($firstname) && empty($lastname) && empty($email))
		{
			$companyid = ' where c.id = '.$companyid;
		}
		else
		{
			$companyid = ' AND c.id = '.$companyid;
		}

		/*if (!empty($selectPerPage) && $selectPerPage !=0 ) 
		{
			$pagelimit = array('1'=>'30','2'=>'50','3'=>'100','4'=>'200');
			$limit = 'limit '.$pagelimit[$selectPerPage];
		}*/

		if ($selectPerPage !=0) 
		{
			$pagelimit = array('1'=>'30','2'=>'50','3'=>'100','4'=>'200');
			$from  = $page * $pagelimit[$selectPerPage];
			$to  = $pagelimit[$selectPerPage];
			$limit = 'limit '.$from.','.$to;
		}
		else
		{
			$from  = $page * $perpage;
			$to  = $perpage;
			$limit = 'limit '.$from.','.$to;
		}


		$currentDate = strtotime(date("d-m-Y",time()));

		if ($firstaccessfrom == $currentDate && $firstaccessto == $currentDate && $lastaccessfrom == $currentDate && $lastaccessto == $currentDate) 
		{
			$dateFilter = '';
		}
		else
		{
			if($firstaccessfrom != $currentDate || $firstaccessto != $currentDate)
			{
				if (empty($param) && empty($companyid)) 
				{
					$dateFilter .= " WHERE u.firstaccess BETWEEN $firstaccessfrom AND $firstaccessto";
				}
				else
				{
					$dateFilter .= " AND u.firstaccess BETWEEN $firstaccessfrom AND $firstaccessto";
				}
			}
			if($lastaccessfrom != $currentDate || $lastaccessto != $currentDate)
			{
				if (empty($param) && empty($companyid) && empty($dateFilter)) 
				{
					$dateFilter .= " WHERE u.lastaccess BETWEEN $lastaccessfrom AND $lastaccessto";
				}
				else
				{
					$dateFilter .= " AND u.lastaccess BETWEEN $lastaccessfrom AND $lastaccessto";
				}
			}
			
		}

		global $DB;
		$data = $DB->get_records_sql("SELECT u.id,u.username,u.firstname,u.lastname,c.name,u.timecreated,u.firstaccess,u.lastaccess,u.email FROM {user} AS u LEFT JOIN {company_users} AS cu ON cu.userid=u.id LEFT JOIN {company} AS c ON c.id=cu.companyid $param $companyid $dateFilter ORDER BY $orderbyParam $limit");
		return $data;
	}

	function totalUser($firstname,$lastname,$email,$orderbyName,$sortingOrder,$companyid,$selectPerPage,$page,$perpage,$firstaccessfrom,$firstaccessto,$lastaccessfrom,$lastaccessto)
	{
		$param = '';
		$dateFilter = '';

		if (!empty($orderbyName)) 
		{
			$orderbyParam = ' u.'.$orderbyName.' '.$sortingOrder;
		}
		else
		{
			$orderbyParam = 'u.id ASC';
		}

		if (!empty($firstname) || !empty($lastname) || !empty($email)) 
		{
			$param = ' where ';
		}

		if (!empty($firstname)) 
		{
			$param .= " u.firstname LIKE '%$firstname%' ";
		}

		if (empty($firstname) && !empty($lastname)) 
		{
			$param .= " u.lastname LIKE '%$lastname%' ";
		}
		elseif (!empty($firstname) && !empty($lastname)) 
		{
			$param .= " AND u.lastname LIKE '%$lastname%' ";
		}

		if (empty($firstname) && empty($lastname) && !empty($email)) 
		{
			$param .= " u.email LIKE '%$email%' ";
		}
		elseif ((!empty($firstname) || !empty($lastname)) && !empty($email)) 
		{
			$param .= " AND u.email LIKE '%$email%' ";
		}

		if ($companyid == 0) 
		{
			$companyid = '';
		}
		elseif(empty($firstname) && empty($lastname) && empty($email))
		{
			$companyid = ' where c.id = '.$companyid;
		}
		else
		{
			$companyid = ' AND c.id = '.$companyid;
		}


		$currentDate = strtotime(date("d-m-Y",time()));

		if (!empty($firstaccessfrom) || !empty($firstaccessto) || !empty($lastaccessfrom) || !empty($lastaccessto)) 
		{
			if ($firstaccessfrom == $currentDate && $firstaccessto == $currentDate && $lastaccessfrom == $currentDate && $lastaccessto == $currentDate) 
			{
				$dateFilter = '';
			}
			else
			{
				if($firstaccessfrom != $currentDate || $firstaccessto != $currentDate)
				{
					if (empty($param) && empty($companyid)) 
					{
						$dateFilter .= " WHERE u.firstaccess BETWEEN $firstaccessfrom AND $firstaccessto";
					}
					else
					{
						$dateFilter .= " AND u.firstaccess BETWEEN $firstaccessfrom AND $firstaccessto";
					}
				}
				if($lastaccessfrom != $currentDate || $lastaccessto != $currentDate)
				{
					if (empty($param) && empty($companyid) && empty($dateFilter)) 
					{
						$dateFilter .= " WHERE u.lastaccess BETWEEN $lastaccessfrom AND $lastaccessto";
					}
					else
					{
						$dateFilter .= " AND u.lastaccess BETWEEN $lastaccessfrom AND $lastaccessto";
					}
				}
				
			}
		}

		global $DB;
		$data = $DB->get_records_sql("SELECT  @seq := FLOOR(@seq + 1) as snum,u.id,u.username,u.firstname,u.lastname,c.name,u.timecreated,u.firstaccess,u.lastaccess,u.email FROM (SELECT @seq := 0) s, {user} AS u LEFT JOIN {company_users} AS cu ON cu.userid=u.id LEFT JOIN {company} AS c ON c.id=cu.companyid $param $companyid $dateFilter ORDER BY $orderbyParam");
		return count($data);
	}

	function countUserLoggedin($userid)
	{
		global $DB;
		$data = $DB->get_record_sql("SELECT count(id) AS total FROM mdl_logstore_standard_log WHERE action = 'loggedin' AND crud = 'r' AND userid = $userid");
		return $data->total;

	}

}

?>

