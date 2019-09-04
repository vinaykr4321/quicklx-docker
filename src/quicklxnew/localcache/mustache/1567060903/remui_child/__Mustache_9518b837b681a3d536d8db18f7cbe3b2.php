<?php

class __Mustache_9518b837b681a3d536d8db18f7cbe3b2 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="stats">
';
        // 'is_siteadmin' section
        $value = $context->find('is_siteadmin');
        $buffer .= $this->sectionB3855c2abdcd33ec7e90a2539069903a($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionD98df53d1e696ade69936d794f906b70(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'enrolleduserstats, theme_remui';
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
                
                $buffer .= 'enrolleduserstats, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBbb174be684eec719922135357edf856(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'quizstats, theme_remui';
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
                
                $buffer .= 'quizstats, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB3855c2abdcd33ec7e90a2539069903a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="card card-shadow" style="clear: both;">
      <div class="card-header card-header-transparent pt-20 pb-0">
        <!-- <label id="stats_name" class="font-weight-600 blue-grey-600 float-right">{{#str}}enrolleduserstats, theme_remui{{/str}}</label> -->
        <ul class="nav nav-tabs nav-tabs-line border-0 chart-action float-left" role="tablist">
          <li class="nav-item" role="presentation">
            <a id="stats_name_one" class="nav-link active" data-toggle="tab" href="#statsOne" aria-controls="statsOne" role="tab"><i class="fa fa-pie-chart"></i> {{#str}}enrolleduserstats, theme_remui{{/str}}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a id="stats_name_two" class="nav-link" data-toggle="tab" href="#statsTwo" aria-controls="statsTwo" role="tab"><i class="fa fa-bar-chart"></i> {{#str}}quizstats, theme_remui{{/str}}</a>
          </li>
        </ul>
      </div>
      <div class="widget-content tab-content bg-white px-20 pt-0 pb-20" data-plugin="tabs">
        <div class="tab-content">
          <div class="tab-pane active" id="statsOne">
            {{> theme_remui/enrolled_users_stats }}
          </div>
          <div class="tab-pane" id="statsTwo">
            {{> theme_remui/quiz_stats }}
          </div>
        </div>
      </div>
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
                
                $buffer .= $indent . '    <div class="card card-shadow" style="clear: both;">
';
                $buffer .= $indent . '      <div class="card-header card-header-transparent pt-20 pb-0">
';
                $buffer .= $indent . '        <!-- <label id="stats_name" class="font-weight-600 blue-grey-600 float-right">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionD98df53d1e696ade69936d794f906b70($context, $indent, $value);
                $buffer .= '</label> -->
';
                $buffer .= $indent . '        <ul class="nav nav-tabs nav-tabs-line border-0 chart-action float-left" role="tablist">
';
                $buffer .= $indent . '          <li class="nav-item" role="presentation">
';
                $buffer .= $indent . '            <a id="stats_name_one" class="nav-link active" data-toggle="tab" href="#statsOne" aria-controls="statsOne" role="tab"><i class="fa fa-pie-chart"></i> ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionD98df53d1e696ade69936d794f906b70($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '          </li>
';
                $buffer .= $indent . '          <li class="nav-item" role="presentation">
';
                $buffer .= $indent . '            <a id="stats_name_two" class="nav-link" data-toggle="tab" href="#statsTwo" aria-controls="statsTwo" role="tab"><i class="fa fa-bar-chart"></i> ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionBbb174be684eec719922135357edf856($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '          </li>
';
                $buffer .= $indent . '        </ul>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      <div class="widget-content tab-content bg-white px-20 pt-0 pb-20" data-plugin="tabs">
';
                $buffer .= $indent . '        <div class="tab-content">
';
                $buffer .= $indent . '          <div class="tab-pane active" id="statsOne">
';
                if ($partial = $this->mustache->loadPartial('theme_remui/enrolled_users_stats')) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '          <div class="tab-pane" id="statsTwo">
';
                if ($partial = $this->mustache->loadPartial('theme_remui/quiz_stats')) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
