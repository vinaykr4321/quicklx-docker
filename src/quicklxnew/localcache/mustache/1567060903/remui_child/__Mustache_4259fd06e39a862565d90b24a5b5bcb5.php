<?php

class __Mustache_4259fd06e39a862565d90b24a5b5bcb5 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="add_notes">
';
        // 'has_courses' section
        $value = $context->find('has_courses');
        $buffer .= $this->section20b2f3fddef4e0be94d6b2af6c269903($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section8cf80b6cded64bbc4e261a94d513b9e5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'addnotes, theme_remui';
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
                
                $buffer .= 'addnotes, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section48a2de42b27acc023dafeb2a796f288c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'selectacourse, theme_remui';
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
                
                $buffer .= 'selectacourse, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4dbe37f6a820378868669c39e5a3d186(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
              <option id="{{ id }}">{{ shortname }}</option>
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
                
                $buffer .= $indent . '              <option id="';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('shortname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</option>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB1366a905d9db0a1de849cdcc6e04bdd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'addsitenote, theme_remui';
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
                
                $buffer .= 'addsitenote, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionAcdcf6d0877a7101dc143fc34467976c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'addcoursenote, theme_remui';
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
                
                $buffer .= 'addcoursenote, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9d108dc9962dd00f5122cd96bf25a909(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'addpersonalnote, theme_remui';
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
                
                $buffer .= 'addpersonalnote, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section20b2f3fddef4e0be94d6b2af6c269903(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="card card-shadow h-350" style="overflow-y: hidden;">
      <div class="card-header card-header-transparent pt-20 pb-0">
        <h5 class="page-aside-title p-0 py-10 m-0">{{#str}}addnotes, theme_remui{{/str}}
        </h5>
      </div>
      <div class="divScroll">
      <div data-role="container">
      <div data-role="content" class="h-250">
      <div class="widget-content tab-content bg-white px-20">
        <div class="add-notes-select">
            <select class="form-control">
              <option value="">{{#str}}selectacourse, theme_remui{{/str}}</option>
              {{# courses }}
              <option id="{{ id }}">{{ shortname }}</option>
              {{/ courses }}
            </select>
        </div>
        <br>
        <select class="select2-studentlist form-control"></select>
        <br><br>
        <div class="row px-15">
          <div class="add-notes-button">
            <a href="#" class="btn btn-xs btn-info site-note">{{#str}}addsitenote, theme_remui{{/str}}</a>
          </div>
          <div class="add-notes-button mx-auto">
            <a href="#" class="btn btn-xs btn-info course-note">{{#str}}addcoursenote, theme_remui{{/str}}</a>
          </div>
          <div class="add-notes-button">
            <a href="#" class="btn btn-xs btn-info personal-note">{{#str}}addpersonalnote, theme_remui{{/str}}</a>
          </div>
        </div>
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
                
                $buffer .= $indent . '    <div class="card card-shadow h-350" style="overflow-y: hidden;">
';
                $buffer .= $indent . '      <div class="card-header card-header-transparent pt-20 pb-0">
';
                $buffer .= $indent . '        <h5 class="page-aside-title p-0 py-10 m-0">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section8cf80b6cded64bbc4e261a94d513b9e5($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '        </h5>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      <div class="divScroll">
';
                $buffer .= $indent . '      <div data-role="container">
';
                $buffer .= $indent . '      <div data-role="content" class="h-250">
';
                $buffer .= $indent . '      <div class="widget-content tab-content bg-white px-20">
';
                $buffer .= $indent . '        <div class="add-notes-select">
';
                $buffer .= $indent . '            <select class="form-control">
';
                $buffer .= $indent . '              <option value="">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section48a2de42b27acc023dafeb2a796f288c($context, $indent, $value);
                $buffer .= '</option>
';
                // 'courses' section
                $value = $context->find('courses');
                $buffer .= $this->section4dbe37f6a820378868669c39e5a3d186($context, $indent, $value);
                $buffer .= $indent . '            </select>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '        <br>
';
                $buffer .= $indent . '        <select class="select2-studentlist form-control"></select>
';
                $buffer .= $indent . '        <br><br>
';
                $buffer .= $indent . '        <div class="row px-15">
';
                $buffer .= $indent . '          <div class="add-notes-button">
';
                $buffer .= $indent . '            <a href="#" class="btn btn-xs btn-info site-note">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB1366a905d9db0a1de849cdcc6e04bdd($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '          <div class="add-notes-button mx-auto">
';
                $buffer .= $indent . '            <a href="#" class="btn btn-xs btn-info course-note">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionAcdcf6d0877a7101dc143fc34467976c($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '          <div class="add-notes-button">
';
                $buffer .= $indent . '            <a href="#" class="btn btn-xs btn-info personal-note">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section9d108dc9962dd00f5122cd96bf25a909($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '          </div>
';
                $buffer .= $indent . '        </div>
';
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

}
