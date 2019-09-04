<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('block_iomad_company_admin_region',
                                                get_string('aws_region', 'block_iomad_company_admin'),
                                                get_string('configawsregion', 'block_iomad_company_admin'),
                                                "",
						PARAM_TEXT));
    $settings->add(new admin_setting_configtext('block_iomad_company_admin_secret',
                                                get_string('aws_secret', 'block_iomad_company_admin'),
                                                get_string('configawssecret', 'block_iomad_company_admin'),
                                                "",
						PARAM_TEXT));
    $settings->add(new admin_setting_configtext('block_iomad_company_admin_key',
                                                get_string('aws_key', 'block_iomad_company_admin'),
                                                get_string('configawskey', 'block_iomad_company_admin'),
                                                "",
						PARAM_TEXT));
}

