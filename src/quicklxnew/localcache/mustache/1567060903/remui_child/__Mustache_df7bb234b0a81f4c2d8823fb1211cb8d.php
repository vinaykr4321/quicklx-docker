<?php

class __Mustache_df7bb234b0a81f4c2d8823fb1211cb8d extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="enrolled_users_stats">
';
        $buffer .= $indent . '  <div class="card card-shadow m-0">
';
        $buffer .= $indent . '    <div class="widget-content tab-content bg-white p-20">
';
        // 'hascategory' section
        $value = $context->find('hascategory');
        $buffer .= $this->section82f68e8e427d0894875797f2a78f0662($context, $indent, $value);
        // 'hascategory' inverted section
        $value = $context->find('hascategory');
        if (empty($value)) {
            
            $buffer .= $indent . '        <div class="enroll-stats-error alert alert-info">
';
            $buffer .= $indent . '          ';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->sectionB14b287b8ab0291db13b0a42df3d7013($context, $indent, $value);
            $buffer .= '
';
            $buffer .= $indent . '        </div>
';
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionB7e993864e528e94fd43100055ed5457(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'selectcategory, theme_remui';
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
                
                $buffer .= 'selectcategory, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section83b2f258fac3a95b81fb83808b0f8f08(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <option data-id="{{ key }}">{{ categoryname }}</option>
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
                
                $buffer .= $indent . '                <option data-id="';
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('categoryname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
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

    private function section999b491c220b3902471cb15ce3af3045(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'nousersincoursecategoryfound, theme_remui';
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
                
                $buffer .= 'nousersincoursecategoryfound, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section82f68e8e427d0894875797f2a78f0662(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="row">
          <div class="col-md-4 offset-md-1">
            <div class="chart-responsive">
              <canvas id="pieChart"></canvas>
            </div>
          </div>
          <div class="col-md-5 offset-md-2">
            <label class="font-weight-400 blue-grey-600 font-size-14">{{#str}}selectcategory, theme_remui{{/str}} :</label>
            <select id=\'coursecategorylist\' class=\'coursecategorylist form-control mb-10\'>
              {{# category }}
                <option data-id="{{ key }}">{{ categoryname }}</option>
              {{/ category }}
            </select>
            <ul class="chart-legend list-group list-group-full clearfix"></ul>
          </div>
        </div>
        <div class="enroll-stats-error alert alert-danger" style="display:none">
          {{#str}}problemwhileloadingdata, theme_remui{{/str}}
        </div>
        <div class="enroll-stats-nouserserror alert alert-info" style="display:none">
          {{#str}}nousersincoursecategoryfound, theme_remui{{/str}}
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
                
                $buffer .= $indent . '        <div class="row">
';
                $buffer .= $indent . '          <div class="col-md-4 offset-md-1">
';
                $buffer .= $indent . '            <div class="chart-responsive">
';
                $buffer .= $indent . '              <canvas id="pieChart"></canvas>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '          <div class="col-md-5 offset-md-2">
';
                $buffer .= $indent . '            <label class="font-weight-400 blue-grey-600 font-size-14">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB7e993864e528e94fd43100055ed5457($context, $indent, $value);
                $buffer .= ' :</label>
';
                $buffer .= $indent . '            <select id=\'coursecategorylist\' class=\'coursecategorylist form-control mb-10\'>
';
                // 'category' section
                $value = $context->find('category');
                $buffer .= $this->section83b2f258fac3a95b81fb83808b0f8f08($context, $indent, $value);
                $buffer .= $indent . '            </select>
';
                $buffer .= $indent . '            <ul class="chart-legend list-group list-group-full clearfix"></ul>
';
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '        <div class="enroll-stats-error alert alert-danger" style="display:none">
';
                $buffer .= $indent . '          ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section46a31c4308536ef2bb4199f8a44985b1($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '        <div class="enroll-stats-nouserserror alert alert-info" style="display:none">
';
                $buffer .= $indent . '          ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section999b491c220b3902471cb15ce3af3045($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB14b287b8ab0291db13b0a42df3d7013(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'nocoursecategoryfound, theme_remui';
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
                
                $buffer .= 'nocoursecategoryfound, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
