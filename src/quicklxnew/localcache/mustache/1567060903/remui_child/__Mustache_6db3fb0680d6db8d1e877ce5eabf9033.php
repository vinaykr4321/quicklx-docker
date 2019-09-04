<?php

class __Mustache_6db3fb0680d6db8d1e877ce5eabf9033 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        if ($partial = $this->mustache->loadPartial('theme_remui/common_start')) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <div class="page-main">
';
        $buffer .= $indent . '        <div class="container">
';
        $buffer .= $indent . '            ';
        $value = $this->resolveValue($context->findDot('output.full_header'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '            <div class="page-content">
';
        $buffer .= $indent . '                <div id="region-main-box">
';
        // 'hasregionmainsettingsmenu' section
        $value = $context->find('hasregionmainsettingsmenu');
        $buffer .= $this->section5397030ddf7634f6279ac7a976f995da($context, $indent, $value);
        $buffer .= $indent . '                    <section id="region-main" ';
        // 'hasblocks' section
        $value = $context->find('hasblocks');
        $buffer .= $this->section1070627cc6d04a30fbc9aa223e9e57ed($context, $indent, $value);
        $buffer .= '>
';
        // 'hasregionmainsettingsmenu' section
        $value = $context->find('hasregionmainsettingsmenu');
        $buffer .= $this->sectionBfb58dd1d13a295db43bf0266e3aa6ee($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '                        ';
        $value = $this->resolveValue($context->findDot('output.course_content_header'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '                        ';
        $value = $this->resolveValue($context->findDot('output.main_content'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '                        ';
        $value = $this->resolveValue($context->findDot('output.course_content_footer'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '                    </section>
';
        $buffer .= $indent . '                    <div class="clearfix"></div>
';
        // 'isTeacher' section
        $value = $context->find('isTeacher');
        $buffer .= $this->section9d6b4816b108246a3ab361d035852a69($context, $indent, $value);
        // 'usercanmanage' section
        $value = $context->find('usercanmanage');
        $buffer .= $this->section1da1c88ecaf80274a56e0c9637dced41($context, $indent, $value);
        // 'hasanalytics' section
        $value = $context->find('hasanalytics');
        $buffer .= $this->section9c361f8f656108d4d3b6abf4dd26cc3a($context, $indent, $value);
        $buffer .= $indent . '                    <ul class="blocks-sm-100 blocks-lg-2 blocks-sm-1">
';
        // 'usercanmanage' section
        $value = $context->find('usercanmanage');
        $buffer .= $this->sectionDee5696a908d3570a43af3d37d047505($context, $indent, $value);
        $buffer .= $indent . '                        <li class="col-sm-12 col-md-12 mb-0 w-full">
';
        if ($partial = $this->mustache->loadPartial('theme_remui/recent_section')) {
            $buffer .= $partial->renderInternal($context, $indent . '                            ');
        }
        $buffer .= $indent . '                        </li>
';
        // 'usercanmanage' inverted section
        $value = $context->find('usercanmanage');
        if (empty($value)) {
            
        }
        $buffer .= $indent . '                    </ul>
';
        $buffer .= $indent . '                </div>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        </div>
';
        if ($partial = $this->mustache->loadPartial('theme_remui/common_end')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section8e3f1445fa2c0d63e7e98ffe86e2359c($context, $indent, $value);

        return $buffer;
    }

    private function section8ae768dbd9f60a7f7df4aaf3cee7aa89(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'has-blocks';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'has-blocks';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5397030ddf7634f6279ac7a976f995da(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <div id="region-main-settings-menu" class="hidden-print {{#hasblocks}}has-blocks{{/hasblocks}}">
                            <div> {{{ output.region_main_settings_menu }}} </div>
                        </div>
                    ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                        <div id="region-main-settings-menu" class="hidden-print ';
                // 'hasblocks' section
                $value = $context->find('hasblocks');
                $buffer .= $this->section8ae768dbd9f60a7f7df4aaf3cee7aa89($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '                            <div> ';
                $value = $this->resolveValue($context->findDot('output.region_main_settings_menu'), $context);
                $buffer .= $value;
                $buffer .= ' </div>
';
                $buffer .= $indent . '                        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1070627cc6d04a30fbc9aa223e9e57ed(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'class="has-blocks"';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'class="has-blocks"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBfb58dd1d13a295db43bf0266e3aa6ee(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <div class="region_main_settings_menu_proxy"></div>
                        ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                            <div class="region_main_settings_menu_proxy"></div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9d6b4816b108246a3ab361d035852a69(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <div class="w-full bg-white my-15 p-15">
                        {{> theme_remui/dashboard_teacher_view_courses}}
                        </div>
                    ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                        <div class="w-full bg-white my-15 p-15">
';
                if ($partial = $this->mustache->loadPartial('theme_remui/dashboard_teacher_view_courses')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                        ');
                }
                $buffer .= $indent . '                        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1da1c88ecaf80274a56e0c9637dced41(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        {{> theme_remui/stats }}
                    ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                if ($partial = $this->mustache->loadPartial('theme_remui/stats')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                        ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9c361f8f656108d4d3b6abf4dd26cc3a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        {{> theme_remui/course_analytics}}
                    ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                if ($partial = $this->mustache->loadPartial('theme_remui/course_analytics')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                        ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDee5696a908d3570a43af3d37d047505(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <li class="col-sm-12 mb-0 float-left">
                            {{> theme_remui/latest_members }}
                        </li>
                        <li class="col-sm-12 mb-0">
                            {{> theme_remui/add_notes }}
                        </li>
                        ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                        <li class="col-sm-12 mb-0 float-left">
';
                if ($partial = $this->mustache->loadPartial('theme_remui/latest_members')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                            ');
                }
                $buffer .= $indent . '                        </li>
';
                $buffer .= $indent . '                        <li class="col-sm-12 mb-0">
';
                if ($partial = $this->mustache->loadPartial('theme_remui/add_notes')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                            ');
                }
                $buffer .= $indent . '                        </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8e3f1445fa2c0d63e7e98ffe86e2359c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'theme_remui/loader\', \'theme_remui/dashboard\', \'theme_remui/slick\', \'theme_remui/jquery.dataTables\', \'theme_remui/dataTables.bootstrap4\'], function () {
    Breakpoints();
    /**
    * Timeline - Course Overview Block
    */
    var alreadyDone = false;
    (function ($, sr) {
        var debounce = function (func, threshold, execAsap) {
            var timeout;
            return function debounced() {
                var obj = this, args = arguments;
                function delayed() {
                    if (!execAsap)
                        func.apply(obj, args);
                    timeout = null;
                };
                if (timeout) {clearTimeout(timeout);
                } else if (execAsap) {func.apply(obj, args);}
                timeout = setTimeout(delayed, threshold || 100);
            };
        }
        jQuery.fn[sr] = function (fn) { return fn ? this.on(\'DOMNodeInserted\', debounce(fn)) : this.trigger(sr); };
    })(jQuery, \'debouncedDNI\');

    jQuery(document).ready(function () {
        jQuery(\'#wdm-timeline-event\').debouncedDNI(function () {
            if (!alreadyDone) {
                jQuery(this).find(\'#myoverview_today_view .carousel-item:first\').addClass(\'active\');
                jQuery(this).find(\'#myoverview_future_view .carousel-item:first\').addClass(\'active\');
                jQuery(this).find(\'#myoverview_overdue_view .carousel-item:first\').addClass(\'active\');

                var today = jQuery(this).find(\'#myoverview_today_view .carousel-item\');
                jQuery(this).find(\'.today .badge.badge-pill\').html(today.length);

                var future = jQuery(this).find(\'#myoverview_future_view .carousel-item\');
                jQuery(this).find(\'.future .badge.badge-pill\').html(future.length);

                var overdue = jQuery(this).find(\'#myoverview_overdue_view .carousel-item\');
                jQuery(this).find(\'.overdue .badge.badge-pill\').html(overdue.length);

                jQuery(".carousel-inner > li.list-group-item.event-list-item").css("display", "none");
                alreadyDone = true;
            }
        });
    });

    jQuery(\'#course-overview-section a[data-toggle="tab"]\').on(\'shown.bs.tab\', function (e) {
        jQuery(\'.wdm-overview-slider\').slick(\'unslick\');
        jQuery(\'.wdm-overview-slider\').slick({
        dots: true,
        arrows: true,
        prevArrow:"<button type=\'button\' class=\'slick-prev pull-left\'><i class=\'fa fa-chevron-left\' aria-hidden=\'true\'></i></button>",
        nextArrow:"<button type=\'button\' class=\'slick-next pull-right\'><i class=\'fa-arrow-circle-right\' aria-hidden=\'true\'></i></button>",
        infinite: true,
        opacity: 0,
        rtl: (jQuery("html").attr("dir") == "rtl") ? true : false,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3
            }
            }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
            }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            }
        ]
    });
    });

    jQuery(\'.wdm-overview-slider\').slick({
        dots: true,
        arrows: true,
        prevArrow:"<button type=\'button\' class=\'slick-prev pull-left\'><i class=\'fa fa-chevron-left\' aria-hidden=\'true\'></i></button>",
        nextArrow:"<button type=\'button\' class=\'slick-next pull-right\'><i class=\'fa-arrow-circle-right\' aria-hidden=\'true\'></i></button>",
        infinite: true,
        rtl: (jQuery("html").attr("dir") == "rtl") ? true : false,
        opacity: 0,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3
            }
            }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
            }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            }
        ]
    });

    jQuery(\'#recent-section .nav a[data-toggle="tab"]\').on(\'shown.bs.tab\', function (e) {
        jQuery(\'#recent-section .divScroll\').each(function() {
            jQuery(this).asScrollable(\'disable\');
            jQuery(this).asScrollable(\'enable\');
        });
    });

    //*****************
    // This is code is for table creation on dashboard
    // this code also toggles between course progress and student progress table
    // Function createDatatable() creates course progress table


    function createDatatable()
    {
        var data;

        if ( jQuery.fn.dataTable.isDataTable( \'#DataTables_Teacher\' ) ) {
            data = jQuery(\'#DataTables_Teacher\').DataTable();
        }
        else {
            data = jQuery(\'#DataTables_Teacher\').DataTable({
                        "paging":   true,
                        "retrieve": true,
                        "pagingType": "simple_numbers",
                        "lengthchange":false,
                        "autoWidth": true,
                        "scrollX": true,
                        "search": "Fred",
                        "lengthChange": false,
                        "info":false,
                        language: {
                            searchPlaceholder: "Search"
                        },
                        responsive: true,
                    });
        }
        {{! jQuery(\'div.dataTables_filter input\').addClass(\'form-control\'); }}
        {{! jQuery(\'div.dataTables_length select\').addClass(\'form-control\'); }}
        return data;
    }
    // call to the createDatatable for course progress
    var teacherViewTable = createDatatable();



    // Destroy the table and send ajax request
    jQuery(\'.wdm_course_name\').on( \'click\', function () {

        var courseid = jQuery(this).data(\'courseid\');
        teacherViewTable.destroy();
        jQuery(\'#DataTables_Teacher\').hide();
        getCourseProgressData(courseid);

    });


    // Restore the previous table
    // jQuery(\'#courserevertbtn\').on( \'click\', function () {
    jQuery(\'body\').on(\'click\', \'#courserevertbtn\',function(){
        courseProgressTable.destroy();
        jQuery(\'.student_progress_ele\').empty();
        jQuery(\'#DataTables_Teacher\').show();
        teacherViewTable = createDatatable();

    });

    // This function will retrieve the student progress
    var courseProgressTable;
    function getCourseProgressData(courseid){

        jQuery.ajax({
            type: "GET",
            async: true,
            url: M.cfg.wwwroot + \'/theme/remui/request_handler.php?action=get_course_progress_ajax&courseid=\' + courseid,
            success: function (response) {
                jQuery(\'div.student_progress_ele\').empty();
                jQuery(\'div.student_progress_ele\').append(response);

                jQuery(\'.pie-progress\').asPieProgress({
                    namespace: \'pie-progress\',
                    speed: 30,
                    classes: {
                        svg: \'pie-progress-svg\',
                        element: \'pie-progress\',
                        number: \'pie-progress-number\',
                        content: \'pie-progress-content\'
                    }
                });

                courseProgressTable = jQuery(\'#wdmCourseProgressTable\').DataTable({
                    "scrollY":        "300px",
                    "scrollCollapse": true,
                    "paging":false,
                    "retrieve": true,
                    "lengthchange":false,
                    "autoWidth": true,
                    "scrollX": true,
                    "search": "Fred",
                    "info":false,
                    language: {
                        searchPlaceholder: "Search"
                    },
                    responsive: true,
                });

                jQuery(\'div.dataTables_filter input\').addClass(\'form-control\');
                jQuery(\'div.dataTables_length select\').addClass(\'form-control\');
            },
            error: function (xhr, status, error) {
                console.log("we are not here ");
                jQuery(\'div#analysis-chart-area\').hide();
            }
        });

    }


    //******************
    // This block opens modal and sends message to user

    jQuery(\'body\').on(\'click\', \'.custom-message\',function(){
        var studentid = jQuery(this).data(\'studentid\');
        console.log(studentid);
        jQuery(\'#messageidhidden\').val(studentid);
    });

    jQuery(\'body\').on(\'click\', \'.send-message\',function(){
        var studentid = jQuery(\'#messageidhidden\').val();
        var message   = jQuery(\'#messagearea\').val();

        if(message != \'\') {
            sendMessageToUser(studentid, message);
        } else {
            jQuery(\'#messagearea\').focus();
        }
    });



    function sendMessageToUser(studentid, message){

        jQuery.ajax({
            type: "GET",
            async: true,
            url: M.cfg.wwwroot + \'/theme/remui/request_handler.php?action=send_message_user_ajax&studentid=\' + studentid +\'&message=\'+message,
            success: function (response) {
                clearModalFields();
                jQuery(\'.close-message\').click();
            },
            error: function (xhr, status, error) {
                jQuery(\'div#analysis-chart-area\').hide();
            }
        });

    }


    function clearModalFields()
    {
        jQuery(\'#messageidhidden\').val(\'\');
        jQuery(\'#messagearea\').val(\'\');
    }
    //***********************************
});
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . 'require([\'theme_remui/loader\', \'theme_remui/dashboard\', \'theme_remui/slick\', \'theme_remui/jquery.dataTables\', \'theme_remui/dataTables.bootstrap4\'], function () {
';
                $buffer .= $indent . '    Breakpoints();
';
                $buffer .= $indent . '    /**
';
                $buffer .= $indent . '    * Timeline - Course Overview Block
';
                $buffer .= $indent . '    */
';
                $buffer .= $indent . '    var alreadyDone = false;
';
                $buffer .= $indent . '    (function ($, sr) {
';
                $buffer .= $indent . '        var debounce = function (func, threshold, execAsap) {
';
                $buffer .= $indent . '            var timeout;
';
                $buffer .= $indent . '            return function debounced() {
';
                $buffer .= $indent . '                var obj = this, args = arguments;
';
                $buffer .= $indent . '                function delayed() {
';
                $buffer .= $indent . '                    if (!execAsap)
';
                $buffer .= $indent . '                        func.apply(obj, args);
';
                $buffer .= $indent . '                    timeout = null;
';
                $buffer .= $indent . '                };
';
                $buffer .= $indent . '                if (timeout) {clearTimeout(timeout);
';
                $buffer .= $indent . '                } else if (execAsap) {func.apply(obj, args);}
';
                $buffer .= $indent . '                timeout = setTimeout(delayed, threshold || 100);
';
                $buffer .= $indent . '            };
';
                $buffer .= $indent . '        }
';
                $buffer .= $indent . '        jQuery.fn[sr] = function (fn) { return fn ? this.on(\'DOMNodeInserted\', debounce(fn)) : this.trigger(sr); };
';
                $buffer .= $indent . '    })(jQuery, \'debouncedDNI\');
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    jQuery(document).ready(function () {
';
                $buffer .= $indent . '        jQuery(\'#wdm-timeline-event\').debouncedDNI(function () {
';
                $buffer .= $indent . '            if (!alreadyDone) {
';
                $buffer .= $indent . '                jQuery(this).find(\'#myoverview_today_view .carousel-item:first\').addClass(\'active\');
';
                $buffer .= $indent . '                jQuery(this).find(\'#myoverview_future_view .carousel-item:first\').addClass(\'active\');
';
                $buffer .= $indent . '                jQuery(this).find(\'#myoverview_overdue_view .carousel-item:first\').addClass(\'active\');
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                var today = jQuery(this).find(\'#myoverview_today_view .carousel-item\');
';
                $buffer .= $indent . '                jQuery(this).find(\'.today .badge.badge-pill\').html(today.length);
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                var future = jQuery(this).find(\'#myoverview_future_view .carousel-item\');
';
                $buffer .= $indent . '                jQuery(this).find(\'.future .badge.badge-pill\').html(future.length);
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                var overdue = jQuery(this).find(\'#myoverview_overdue_view .carousel-item\');
';
                $buffer .= $indent . '                jQuery(this).find(\'.overdue .badge.badge-pill\').html(overdue.length);
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                jQuery(".carousel-inner > li.list-group-item.event-list-item").css("display", "none");
';
                $buffer .= $indent . '                alreadyDone = true;
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '        });
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    jQuery(\'#course-overview-section a[data-toggle="tab"]\').on(\'shown.bs.tab\', function (e) {
';
                $buffer .= $indent . '        jQuery(\'.wdm-overview-slider\').slick(\'unslick\');
';
                $buffer .= $indent . '        jQuery(\'.wdm-overview-slider\').slick({
';
                $buffer .= $indent . '        dots: true,
';
                $buffer .= $indent . '        arrows: true,
';
                $buffer .= $indent . '        prevArrow:"<button type=\'button\' class=\'slick-prev pull-left\'><i class=\'fa fa-chevron-left\' aria-hidden=\'true\'></i></button>",
';
                $buffer .= $indent . '        nextArrow:"<button type=\'button\' class=\'slick-next pull-right\'><i class=\'fa-arrow-circle-right\' aria-hidden=\'true\'></i></button>",
';
                $buffer .= $indent . '        infinite: true,
';
                $buffer .= $indent . '        opacity: 0,
';
                $buffer .= $indent . '        rtl: (jQuery("html").attr("dir") == "rtl") ? true : false,
';
                $buffer .= $indent . '        speed: 500,
';
                $buffer .= $indent . '        slidesToShow: 4,
';
                $buffer .= $indent . '        slidesToScroll: 4,
';
                $buffer .= $indent . '        responsive: [{
';
                $buffer .= $indent . '            breakpoint: 1024,
';
                $buffer .= $indent . '            settings: {
';
                $buffer .= $indent . '                slidesToShow: 3,
';
                $buffer .= $indent . '                slidesToScroll: 3
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '            }, {
';
                $buffer .= $indent . '            breakpoint: 800,
';
                $buffer .= $indent . '            settings: {
';
                $buffer .= $indent . '                slidesToShow: 2,
';
                $buffer .= $indent . '                slidesToScroll: 2
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '            }, {
';
                $buffer .= $indent . '            breakpoint: 480,
';
                $buffer .= $indent . '            settings: {
';
                $buffer .= $indent . '                slidesToShow: 1,
';
                $buffer .= $indent . '                slidesToScroll: 1
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '        ]
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    jQuery(\'.wdm-overview-slider\').slick({
';
                $buffer .= $indent . '        dots: true,
';
                $buffer .= $indent . '        arrows: true,
';
                $buffer .= $indent . '        prevArrow:"<button type=\'button\' class=\'slick-prev pull-left\'><i class=\'fa fa-chevron-left\' aria-hidden=\'true\'></i></button>",
';
                $buffer .= $indent . '        nextArrow:"<button type=\'button\' class=\'slick-next pull-right\'><i class=\'fa-arrow-circle-right\' aria-hidden=\'true\'></i></button>",
';
                $buffer .= $indent . '        infinite: true,
';
                $buffer .= $indent . '        rtl: (jQuery("html").attr("dir") == "rtl") ? true : false,
';
                $buffer .= $indent . '        opacity: 0,
';
                $buffer .= $indent . '        speed: 500,
';
                $buffer .= $indent . '        slidesToShow: 4,
';
                $buffer .= $indent . '        slidesToScroll: 4,
';
                $buffer .= $indent . '        responsive: [{
';
                $buffer .= $indent . '            breakpoint: 1024,
';
                $buffer .= $indent . '            settings: {
';
                $buffer .= $indent . '                slidesToShow: 3,
';
                $buffer .= $indent . '                slidesToScroll: 3
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '            }, {
';
                $buffer .= $indent . '            breakpoint: 800,
';
                $buffer .= $indent . '            settings: {
';
                $buffer .= $indent . '                slidesToShow: 2,
';
                $buffer .= $indent . '                slidesToScroll: 2
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '            }, {
';
                $buffer .= $indent . '            breakpoint: 480,
';
                $buffer .= $indent . '            settings: {
';
                $buffer .= $indent . '                slidesToShow: 1,
';
                $buffer .= $indent . '                slidesToScroll: 1
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '        ]
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    jQuery(\'#recent-section .nav a[data-toggle="tab"]\').on(\'shown.bs.tab\', function (e) {
';
                $buffer .= $indent . '        jQuery(\'#recent-section .divScroll\').each(function() {
';
                $buffer .= $indent . '            jQuery(this).asScrollable(\'disable\');
';
                $buffer .= $indent . '            jQuery(this).asScrollable(\'enable\');
';
                $buffer .= $indent . '        });
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    //*****************
';
                $buffer .= $indent . '    // This is code is for table creation on dashboard
';
                $buffer .= $indent . '    // this code also toggles between course progress and student progress table
';
                $buffer .= $indent . '    // Function createDatatable() creates course progress table
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    function createDatatable()
';
                $buffer .= $indent . '    {
';
                $buffer .= $indent . '        var data;
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        if ( jQuery.fn.dataTable.isDataTable( \'#DataTables_Teacher\' ) ) {
';
                $buffer .= $indent . '            data = jQuery(\'#DataTables_Teacher\').DataTable();
';
                $buffer .= $indent . '        }
';
                $buffer .= $indent . '        else {
';
                $buffer .= $indent . '            data = jQuery(\'#DataTables_Teacher\').DataTable({
';
                $buffer .= $indent . '                        "paging":   true,
';
                $buffer .= $indent . '                        "retrieve": true,
';
                $buffer .= $indent . '                        "pagingType": "simple_numbers",
';
                $buffer .= $indent . '                        "lengthchange":false,
';
                $buffer .= $indent . '                        "autoWidth": true,
';
                $buffer .= $indent . '                        "scrollX": true,
';
                $buffer .= $indent . '                        "search": "Fred",
';
                $buffer .= $indent . '                        "lengthChange": false,
';
                $buffer .= $indent . '                        "info":false,
';
                $buffer .= $indent . '                        language: {
';
                $buffer .= $indent . '                            searchPlaceholder: "Search"
';
                $buffer .= $indent . '                        },
';
                $buffer .= $indent . '                        responsive: true,
';
                $buffer .= $indent . '                    });
';
                $buffer .= $indent . '        }
';
                $buffer .= $indent . '        return data;
';
                $buffer .= $indent . '    }
';
                $buffer .= $indent . '    // call to the createDatatable for course progress
';
                $buffer .= $indent . '    var teacherViewTable = createDatatable();
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    // Destroy the table and send ajax request
';
                $buffer .= $indent . '    jQuery(\'.wdm_course_name\').on( \'click\', function () {
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        var courseid = jQuery(this).data(\'courseid\');
';
                $buffer .= $indent . '        teacherViewTable.destroy();
';
                $buffer .= $indent . '        jQuery(\'#DataTables_Teacher\').hide();
';
                $buffer .= $indent . '        getCourseProgressData(courseid);
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    // Restore the previous table
';
                $buffer .= $indent . '    // jQuery(\'#courserevertbtn\').on( \'click\', function () {
';
                $buffer .= $indent . '    jQuery(\'body\').on(\'click\', \'#courserevertbtn\',function(){
';
                $buffer .= $indent . '        courseProgressTable.destroy();
';
                $buffer .= $indent . '        jQuery(\'.student_progress_ele\').empty();
';
                $buffer .= $indent . '        jQuery(\'#DataTables_Teacher\').show();
';
                $buffer .= $indent . '        teacherViewTable = createDatatable();
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    // This function will retrieve the student progress
';
                $buffer .= $indent . '    var courseProgressTable;
';
                $buffer .= $indent . '    function getCourseProgressData(courseid){
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        jQuery.ajax({
';
                $buffer .= $indent . '            type: "GET",
';
                $buffer .= $indent . '            async: true,
';
                $buffer .= $indent . '            url: M.cfg.wwwroot + \'/theme/remui/request_handler.php?action=get_course_progress_ajax&courseid=\' + courseid,
';
                $buffer .= $indent . '            success: function (response) {
';
                $buffer .= $indent . '                jQuery(\'div.student_progress_ele\').empty();
';
                $buffer .= $indent . '                jQuery(\'div.student_progress_ele\').append(response);
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                jQuery(\'.pie-progress\').asPieProgress({
';
                $buffer .= $indent . '                    namespace: \'pie-progress\',
';
                $buffer .= $indent . '                    speed: 30,
';
                $buffer .= $indent . '                    classes: {
';
                $buffer .= $indent . '                        svg: \'pie-progress-svg\',
';
                $buffer .= $indent . '                        element: \'pie-progress\',
';
                $buffer .= $indent . '                        number: \'pie-progress-number\',
';
                $buffer .= $indent . '                        content: \'pie-progress-content\'
';
                $buffer .= $indent . '                    }
';
                $buffer .= $indent . '                });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                courseProgressTable = jQuery(\'#wdmCourseProgressTable\').DataTable({
';
                $buffer .= $indent . '                    "scrollY":        "300px",
';
                $buffer .= $indent . '                    "scrollCollapse": true,
';
                $buffer .= $indent . '                    "paging":false,
';
                $buffer .= $indent . '                    "retrieve": true,
';
                $buffer .= $indent . '                    "lengthchange":false,
';
                $buffer .= $indent . '                    "autoWidth": true,
';
                $buffer .= $indent . '                    "scrollX": true,
';
                $buffer .= $indent . '                    "search": "Fred",
';
                $buffer .= $indent . '                    "info":false,
';
                $buffer .= $indent . '                    language: {
';
                $buffer .= $indent . '                        searchPlaceholder: "Search"
';
                $buffer .= $indent . '                    },
';
                $buffer .= $indent . '                    responsive: true,
';
                $buffer .= $indent . '                });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                jQuery(\'div.dataTables_filter input\').addClass(\'form-control\');
';
                $buffer .= $indent . '                jQuery(\'div.dataTables_length select\').addClass(\'form-control\');
';
                $buffer .= $indent . '            },
';
                $buffer .= $indent . '            error: function (xhr, status, error) {
';
                $buffer .= $indent . '                console.log("we are not here ");
';
                $buffer .= $indent . '                jQuery(\'div#analysis-chart-area\').hide();
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '        });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    }
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    //******************
';
                $buffer .= $indent . '    // This block opens modal and sends message to user
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    jQuery(\'body\').on(\'click\', \'.custom-message\',function(){
';
                $buffer .= $indent . '        var studentid = jQuery(this).data(\'studentid\');
';
                $buffer .= $indent . '        console.log(studentid);
';
                $buffer .= $indent . '        jQuery(\'#messageidhidden\').val(studentid);
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    jQuery(\'body\').on(\'click\', \'.send-message\',function(){
';
                $buffer .= $indent . '        var studentid = jQuery(\'#messageidhidden\').val();
';
                $buffer .= $indent . '        var message   = jQuery(\'#messagearea\').val();
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        if(message != \'\') {
';
                $buffer .= $indent . '            sendMessageToUser(studentid, message);
';
                $buffer .= $indent . '        } else {
';
                $buffer .= $indent . '            jQuery(\'#messagearea\').focus();
';
                $buffer .= $indent . '        }
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    function sendMessageToUser(studentid, message){
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        jQuery.ajax({
';
                $buffer .= $indent . '            type: "GET",
';
                $buffer .= $indent . '            async: true,
';
                $buffer .= $indent . '            url: M.cfg.wwwroot + \'/theme/remui/request_handler.php?action=send_message_user_ajax&studentid=\' + studentid +\'&message=\'+message,
';
                $buffer .= $indent . '            success: function (response) {
';
                $buffer .= $indent . '                clearModalFields();
';
                $buffer .= $indent . '                jQuery(\'.close-message\').click();
';
                $buffer .= $indent . '            },
';
                $buffer .= $indent . '            error: function (xhr, status, error) {
';
                $buffer .= $indent . '                jQuery(\'div#analysis-chart-area\').hide();
';
                $buffer .= $indent . '            }
';
                $buffer .= $indent . '        });
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    }
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    function clearModalFields()
';
                $buffer .= $indent . '    {
';
                $buffer .= $indent . '        jQuery(\'#messageidhidden\').val(\'\');
';
                $buffer .= $indent . '        jQuery(\'#messagearea\').val(\'\');
';
                $buffer .= $indent . '    }
';
                $buffer .= $indent . '    //***********************************
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
