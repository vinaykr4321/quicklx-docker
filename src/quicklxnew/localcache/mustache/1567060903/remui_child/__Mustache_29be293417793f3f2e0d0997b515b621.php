<?php

class __Mustache_29be293417793f3f2e0d0997b515b621 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="quiz_stats">
';
        // 'has_courses_for_quiz' section
        $value = $context->find('has_courses_for_quiz');
        $buffer .= $this->sectionA7458cd5ed4294dad1f794e3529271dd($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionFe80ba37f864bbcee3a7034e94cf1c1e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <option data-id="{{ courseid }}">{{{ shortname }}}</option>
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
                
                $buffer .= $indent . '            <option data-id="';
                $value = $this->resolveValue($context->find('courseid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('shortname'), $context);
                $buffer .= $value;
                $buffer .= '</option>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section46a31c4308536ef2bb4199f8a44985b1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'problemwhileloadingdata, theme_remui';
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
                
                $buffer .= 'problemwhileloadingdata, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA7458cd5ed4294dad1f794e3529271dd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="card card-shadow m-0">
      <div class="widget-content tab-content bg-white p-20">
        <select id="quiz-course-list" class="form-control mb-30">
          {{# courses_for_quiz}}
            <option data-id="{{ courseid }}">{{{ shortname }}}</option>
          {{/ courses_for_quiz}}
        </select>
        <div id="quiz-chart-area">
          <div class="chart">
            <canvas id="barChart"></canvas>
          </div>
        </div>
        <div class="quiz-stats-error alert alert-danger" style="display:none">
          {{#str}}problemwhileloadingdata, theme_remui{{/str}}
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
                
                $buffer .= $indent . '    <div class="card card-shadow m-0">
';
                $buffer .= $indent . '      <div class="widget-content tab-content bg-white p-20">
';
                $buffer .= $indent . '        <select id="quiz-course-list" class="form-control mb-30">
';
                // 'courses_for_quiz' section
                $value = $context->find('courses_for_quiz');
                $buffer .= $this->sectionFe80ba37f864bbcee3a7034e94cf1c1e($context, $indent, $value);
                $buffer .= $indent . '        </select>
';
                $buffer .= $indent . '        <div id="quiz-chart-area">
';
                $buffer .= $indent . '          <div class="chart">
';
                $buffer .= $indent . '            <canvas id="barChart"></canvas>
';
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '        <div class="quiz-stats-error alert alert-danger" style="display:none">
';
                $buffer .= $indent . '          ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section46a31c4308536ef2bb4199f8a44985b1($context, $indent, $value);
                $buffer .= '
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
