<?php

class __Mustache_b46e87fb75bc1d5d7287984fbce06329 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="recent_assignments">
';
        // 'hasrecentassignments' section
        $value = $context->find('hasrecentassignments');
        $buffer .= $this->sectionFb7a54e3f2a1a55adcd6556afdbad69b($context, $indent, $value);
        // 'hasrecentassignments' inverted section
        $value = $context->find('hasrecentassignments');
        if (empty($value)) {
            
            $buffer .= $indent . '    <div class="card card-shadow mh-100 mx-0" data-name="recentfeedback" style="overflow-y: hidden;">
';
            // 'hasrecentfeedback' section
            $value = $context->find('hasrecentfeedback');
            $buffer .= $this->section22e2286f18c7bec1aa9726811e17e025($context, $indent, $value);
            // 'hasrecentfeedback' inverted section
            $value = $context->find('hasrecentfeedback');
            if (empty($value)) {
                
                $buffer .= $indent . '        <div class="widget-content tab-content bg-white p-20">
';
                $buffer .= $indent . '          ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section6064bb7fce17add83de342dbf44e530b($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </div>
';
            }
            $buffer .= $indent . '    </div>
';
        }
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section028e961e735b9df9d12ae3ead5122a5e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'activity, moodle';
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
                
                $buffer .= 'activity, moodle';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD75223b84ca302f0c530113fb878b26c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'course, moodle';
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
                
                $buffer .= 'course, moodle';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1b31df491f74bd7d0c32a235d6224ea6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <a href="{{ cm_url }}" class="list-group-item">
          <span>
            {{#str}}activity, moodle{{/str}} : {{{ cm_name }}}
          </span>
          <br>
          <span class="text-muted">
            {{#str}}course, moodle{{/str}} : {{{ course_fullname }}}
          </span>
        </a>
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
                
                $buffer .= $indent . '        <a href="';
                $value = $this->resolveValue($context->find('cm_url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="list-group-item">
';
                $buffer .= $indent . '          <span>
';
                $buffer .= $indent . '            ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section028e961e735b9df9d12ae3ead5122a5e($context, $indent, $value);
                $buffer .= ' : ';
                $value = $this->resolveValue($context->find('cm_name'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '          </span>
';
                $buffer .= $indent . '          <br>
';
                $buffer .= $indent . '          <span class="text-muted">
';
                $buffer .= $indent . '            ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionD75223b84ca302f0c530113fb878b26c($context, $indent, $value);
                $buffer .= ' : ';
                $value = $this->resolveValue($context->find('course_fullname'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '          </span>
';
                $buffer .= $indent . '        </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFb7a54e3f2a1a55adcd6556afdbad69b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="card card-shadow mh-100 mx-0" data-name="recentassignments" style="overflow-y: hidden;">
      <div class="divScroll">
      <div data-role="container" class="h-300 mb-20">
      <div data-role="content">
      <div class="widget-content tab-content bg-white p-20">
      {{# recentassignments }}
        <a href="{{ cm_url }}" class="list-group-item">
          <span>
            {{#str}}activity, moodle{{/str}} : {{{ cm_name }}}
          </span>
          <br>
          <span class="text-muted">
            {{#str}}course, moodle{{/str}} : {{{ course_fullname }}}
          </span>
        </a>
      {{/ recentassignments }}
      </div>
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
                
                $buffer .= $indent . '    <div class="card card-shadow mh-100 mx-0" data-name="recentassignments" style="overflow-y: hidden;">
';
                $buffer .= $indent . '      <div class="divScroll">
';
                $buffer .= $indent . '      <div data-role="container" class="h-300 mb-20">
';
                $buffer .= $indent . '      <div data-role="content">
';
                $buffer .= $indent . '      <div class="widget-content tab-content bg-white p-20">
';
                // 'recentassignments' section
                $value = $context->find('recentassignments');
                $buffer .= $this->section1b31df491f74bd7d0c32a235d6224ea6($context, $indent, $value);
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
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

    private function section04e866849ee2dd3ee25ae783dea064d7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'grade ,moodle';
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
                
                $buffer .= 'grade ,moodle';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section59d39e0d82451a20dd18fb7c60cb8431(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'time, moodle';
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
                
                $buffer .= 'time, moodle';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionF2d846e0a96ee1884b6f074a23bae7a7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{ timemodified }}, %d %B %Y, %I:%M %p ';
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
                
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('timemodified'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ', %d %B %Y, %I:%M %p ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section936a40363f2c1915cde98e7ea7a607aa(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <tr>
              <td><a href="{{ courseurl }}" style="text-decoration: none;">{{ course_shortname }}</a></td>
              <td><a href="{{ assignurl }}" style="text-decoration: none;">{{ grade_itemname }}</a></td>
              <td>{{ grade_rawgrade }} / {{ grade_rawgrademax }}</td>
              <td>
                <span class="text-info">
                  <i class="fa fa-clock-o"></i>
                  {{#userdate}} {{ timemodified }}, %d %B %Y, %I:%M %p {{/userdate}}
                </span>
              </td>
            </tr>
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
                
                $buffer .= $indent . '            <tr>
';
                $buffer .= $indent . '              <td><a href="';
                $value = $this->resolveValue($context->find('courseurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" style="text-decoration: none;">';
                $value = $this->resolveValue($context->find('course_shortname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></td>
';
                $buffer .= $indent . '              <td><a href="';
                $value = $this->resolveValue($context->find('assignurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" style="text-decoration: none;">';
                $value = $this->resolveValue($context->find('grade_itemname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></td>
';
                $buffer .= $indent . '              <td>';
                $value = $this->resolveValue($context->find('grade_rawgrade'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' / ';
                $value = $this->resolveValue($context->find('grade_rawgrademax'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</td>
';
                $buffer .= $indent . '              <td>
';
                $buffer .= $indent . '                <span class="text-info">
';
                $buffer .= $indent . '                  <i class="fa fa-clock-o"></i>
';
                $buffer .= $indent . '                  ';
                // 'userdate' section
                $value = $context->find('userdate');
                $buffer .= $this->sectionF2d846e0a96ee1884b6f074a23bae7a7($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                </span>
';
                $buffer .= $indent . '              </td>
';
                $buffer .= $indent . '            </tr>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22e2286f18c7bec1aa9726811e17e025(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
      <div class="divScroll">
      <div data-role="container" class="h-300 mb-20">
      <div data-role="content">
      <div class="box-body table-responsive px-15">
        <table class="table table-hover">
          <tbody>
            <tr>
              <th>{{#str}}course, moodle{{/str}}</th>
              <th>{{#str}}activity, moodle{{/str}}</th>
              <th>{{#str}}grade ,moodle{{/str}}</th>
              <th>{{#str}}time, moodle{{/str}}</th>
            </tr>
            {{# recentfeedback }}
            <tr>
              <td><a href="{{ courseurl }}" style="text-decoration: none;">{{ course_shortname }}</a></td>
              <td><a href="{{ assignurl }}" style="text-decoration: none;">{{ grade_itemname }}</a></td>
              <td>{{ grade_rawgrade }} / {{ grade_rawgrademax }}</td>
              <td>
                <span class="text-info">
                  <i class="fa fa-clock-o"></i>
                  {{#userdate}} {{ timemodified }}, %d %B %Y, %I:%M %p {{/userdate}}
                </span>
              </td>
            </tr>
            {{/ recentfeedback }}
          </tbody>
        </table>
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
                
                $buffer .= $indent . '      <div class="divScroll">
';
                $buffer .= $indent . '      <div data-role="container" class="h-300 mb-20">
';
                $buffer .= $indent . '      <div data-role="content">
';
                $buffer .= $indent . '      <div class="box-body table-responsive px-15">
';
                $buffer .= $indent . '        <table class="table table-hover">
';
                $buffer .= $indent . '          <tbody>
';
                $buffer .= $indent . '            <tr>
';
                $buffer .= $indent . '              <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionD75223b84ca302f0c530113fb878b26c($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '              <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section028e961e735b9df9d12ae3ead5122a5e($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '              <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section04e866849ee2dd3ee25ae783dea064d7($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '              <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section59d39e0d82451a20dd18fb7c60cb8431($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '            </tr>
';
                // 'recentfeedback' section
                $value = $context->find('recentfeedback');
                $buffer .= $this->section936a40363f2c1915cde98e7ea7a607aa($context, $indent, $value);
                $buffer .= $indent . '          </tbody>
';
                $buffer .= $indent . '        </table>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6064bb7fce17add83de342dbf44e530b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'norecentfeedback, theme_remui';
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
                
                $buffer .= 'norecentfeedback, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
