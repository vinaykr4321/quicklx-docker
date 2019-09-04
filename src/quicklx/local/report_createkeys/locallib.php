<?php

/**
 *  Filter form used on the report .
 *
 */
require_once($CFG->dirroot . '/local/base/locallib.php');

class report_createkeys
{

    public static function getmax_batchid()
    {
        global $DB;
        $maxvalue = $DB->get_record_sql("SELECT max(batch_setting_id) as max FROM `mdl_key_batches`");
        if (empty($maxvalue)) {
            $value == 1;
        } else {
            $value = ($maxvalue->max + 1);
        }
        return $value;
    }

    public static function getdata($params)
    {
        global $DB;

        if (isset($params['page']))
            $page = $params['page'];
        else
            $page = 0;
        if (isset($params['perpage']))
            $perpage = $params['perpage'];
        if (isset($params['urldatefrom']))
            $datefrom = $params['urldatefrom'];
        if (isset($params['urldateto']))
            $dateto = $params['urldateto'];

        if($params['download']){
             $sql = "SELECT @s:=@s+1 n,kb.*,kbs.* FROM mdl_key_batches kb JOIN mdl_key_batch_settings kbs ON kb.batch_setting_id = kbs.id, (SELECT @s:=0) n  Where timecreated BETWEEN  $datefrom AND $dateto ORDER BY n DESC";
             $data = $DB->get_records_sql($sql);

        }else{
             $sql = "SELECT @s:=@s+1 n,kb.*,kbs.* FROM mdl_key_batches kb JOIN mdl_key_batch_settings kbs ON kb.batch_setting_id = kbs.id, (SELECT @s:=0) n  Where timecreated BETWEEN  $datefrom AND $dateto ORDER BY n DESC";
             $data = $DB->get_records_sql($sql, null, $page * $perpage, $perpage);
        }

        if (!empty($data)) {
            return $data;
        }
    }

   

    public static function getdatacount($params)
    {
        global $DB;
        if (isset($params['urldatefrom']))
            $datefrom = $params['urldatefrom'];
        if (isset($params['urldateto']))
            $dateto = $params['urldateto'];
        $sql = "SELECT count(*) as count FROM mdl_key_batches kb JOIN mdl_key_batch_settings kbs ON kb.batch_setting_id = kbs.id Where timecreated BETWEEN  $datefrom AND $dateto";
        $datacount = $DB->get_record_sql($sql);
        return $datacount->count;
    }
}

class iomad_createkeys_activity_filter_form extends moodleform
{
    

    public function definition()
    {
        global $CFG, $DB, $USER, $SESSION;

        $mform = &$this->_form;

        $selectcompany = self::getcompanylist();
        $context = context_system::instance();
        $selectedcompany =  iomad::get_my_companyid($context);

        $mform->addElement('header', 'daterangefields', format_string(get_string('createkeys', 'local_report_createkeys')));
        $mform->setExpanded('daterangefields', true);
        $mform->addElement('select', 'company', '<b>' . get_string('company', 'local_report_createkeys') . ':</b>', $selectcompany, 'style="width: 40% !important;"');
        $mform->setDefault('company',$selectedcompany);




        $keytypeselect = array('1' => 'One-time','2' => 'Unlimited');
        $mform->addElement('select', 'keytype', '<b>' . get_string('keytype', 'local_report_createkeys') . ':</b>', $keytypeselect, 'style="width: 40% !important;"');

        $mform->addElement('text', 'noofkeys', '<b>' . get_string('noofkeys', 'local_report_createkeys') . ':</b>', 'style="width: 40% !important;"'); 

        $mform->setType('noofkeys', PARAM_TEXT);

        $mform->addRule('noofkeys', get_string('missingnoofkeys', 'local_report_createkeys'), 'required', null, 'client');


        $mform->addElement('text', 'noofused', '<b>' . get_string('noofused', 'local_report_createkeys') . ':</b>', 'style="width: 40% !important;"');

        //$mform->addRule('noofused', get_string('missingnoofused', 'local_report_createkeys'), 'required', null, 'server');


        $mform->hideIf('noofused', 'keytype', 'eq', 1);

        $mform->addElement('text', 'batchname', '<b>' . get_string('batchname', 'local_report_createkeys') . ':</b>', 'style="width: 40% !important;"');

        $mform->addRule('batchname', get_string('batchnameerror', 'local_report_createkeys'), 'required', null, 'client');


        $select =  $mform->addElement('select', 'selectlicense', '<b>' . get_string('selectlicense', 'local_report_createkeys') . ':</b>', $dateranges, 'style="width: 40% !important;"', 'required');

        $select->setMultiple(true);
        $mform->addRule('selectlicense', get_string('missinglicense', 'local_report_createkeys'), 'required', null, 'client');

        $from = array();
        $from[] = &$mform->createElement('date_selector', 'keyexpiredate', '');
        $from[] = &$mform->createElement('checkbox', 'enable', '', get_string('enable'));
        $mform->addGroup($from, 'keyexpiredate', '<b>' . get_string('from', 'local_report_createkeys') . ':</b>', ' ',  false);
        $mform->disabledIf('keyexpiredate', 'enable');

       // $mform->setDefault('datefrom', strtotime(date('Y-m-d', strtotime('today - 30 days'))));
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('createnewkeys', 'local_report_createkeys'));

        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        $mform->closeHeaderBefore('buttonar');
    }
    
    public function getcompanylist()
    {
        global $CFG, $DB, $USER, $SESSION;

        $companylist = $DB->get_records_sql("SELECT id,name from {company} where suspended = 0 ORDER BY name ASC");
        $companylistarray = array();
        foreach ($companylist as $value) {
            $companylistarray[$value->id] = $value->name;
        }
        return $companylistarray;
    }


}


class iomad_login_activity_filter_form extends moodleform
{

    protected $params = array();

    public function __construct($params)
    {
        $this->params = $params;
        parent::__construct();
    }

    public function definition()
    {
        global $CFG, $DB, $USER, $SESSION;

        $mform = &$this->_form;

        $mform->addElement('html', '<div class="row button_middle btns-createnewkeys"><div><a href="createkeys.php"><button type="button" class="btn btn-primary">Create New Key Batch</button></a></div><div class="button_space_value"><a href="#"><button type="button" class="btn btn-secondary colours">Delete Key Batch</button></a></div> </div>');

        $mform->addElement('header', 'daterangefields', format_string(get_string('daterangeheader', 'local_report_createkeys')));
        $mform->setExpanded('daterangefields', true);
        $dateranges = array('no' => get_string('no', 'local_base'), 'sessionstart' => get_string('sessionstart', 'local_base'));

        $mform->addElement('date_selector', 'datefrom', '<b>' . get_string('datefrom', 'local_base') . ':</b>');
        $mform->addElement('date_selector', 'dateto', '<b>' . get_string('dateto', 'local_base') . ':</b>');

        $mform->setDefault('daterange', 'datecomp');
        $mform->setDefault('datefrom', strtotime(date('Y-m-d', strtotime('today - 30 days'))));

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('updatereport', 'local_base'));
        $buttonarray[] = $mform->createElement('button', 'removefilter', get_string('removefilters', 'local_base'), array('class' => 'remove-btn'));

        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        $mform->closeHeaderBefore('buttonar');
    }
}
