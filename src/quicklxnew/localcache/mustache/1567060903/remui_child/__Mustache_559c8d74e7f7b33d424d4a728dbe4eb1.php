<?php

class __Mustache_559c8d74e7f7b33d424d4a728dbe4eb1 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="latest_members">
';
        // 'is_siteadmin' section
        $value = $context->find('is_siteadmin');
        $buffer .= $this->sectionEcaeb18804154c1e826b18603e0b209b($context, $indent, $value);
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionDd167c70bfb6006da686f6efe04a6e2a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'latestmembers, theme_remui';
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
                
                $buffer .= 'latestmembers, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6e756cc272c65cee89d46abf1d61f8e3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'userimage, theme_remui';
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
                
                $buffer .= 'userimage, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section89e0ce42e1d84b1187233452830762f8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <li class="list-inline-item">
              <img src="{{ img }}" alt="{{#str}}userimage, theme_remui{{/str}}">
                <a class="users-list-name" href="{{ profile_url }}={{ id }}">{{ name }}</a>
                <span class="users-list-date">{{ register_date }}</span>
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
                
                $buffer .= $indent . '            <li class="list-inline-item">
';
                $buffer .= $indent . '              <img src="';
                $value = $this->resolveValue($context->find('img'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" alt="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section6e756cc272c65cee89d46abf1d61f8e3($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '                <a class="users-list-name" href="';
                $value = $this->resolveValue($context->find('profile_url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '=';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '                <span class="users-list-date">';
                $value = $this->resolveValue($context->find('register_date'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>
';
                $buffer .= $indent . '            </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE023611d899a4af9cc4668ab37d3367b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'viewallusers, theme_remui';
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
                
                $buffer .= 'viewallusers, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionEcaeb18804154c1e826b18603e0b209b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="card card-shadow h-350" style="overflow-y: hidden;">
      <div class="card-header card-header-transparent pt-20 pb-0">
        <h5 class="page-aside-title p-0 py-10 m-0">
          <!-- <i class="fa fa-users" aria-hidden="true"></i> --> {{#str}}latestmembers, theme_remui{{/str}}
        </h5>
      </div>
      <div class="divScroll">
      <div data-role="container">
      <div data-role="content">
      <div class="widget-content tab-content bg-white p-20">
        <ul class="users-list">
          {{# latest_members }}
            <li class="list-inline-item">
              <img src="{{ img }}" alt="{{#str}}userimage, theme_remui{{/str}}">
                <a class="users-list-name" href="{{ profile_url }}={{ id }}">{{ name }}</a>
                <span class="users-list-date">{{ register_date }}</span>
            </li>
          {{/ latest_members }}
        </ul>
        <div class="text-center pt-15" style="clear: both;">
          <a href="{{ user_profiles }}" class="uppercase">{{#str}}viewallusers, theme_remui{{/str}}</a>
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
                $buffer .= $indent . '        <h5 class="page-aside-title p-0 py-10 m-0">
';
                $buffer .= $indent . '          <!-- <i class="fa fa-users" aria-hidden="true"></i> --> ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionDd167c70bfb6006da686f6efe04a6e2a($context, $indent, $value);
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
                $buffer .= $indent . '      <div data-role="content">
';
                $buffer .= $indent . '      <div class="widget-content tab-content bg-white p-20">
';
                $buffer .= $indent . '        <ul class="users-list">
';
                // 'latest_members' section
                $value = $context->find('latest_members');
                $buffer .= $this->section89e0ce42e1d84b1187233452830762f8($context, $indent, $value);
                $buffer .= $indent . '        </ul>
';
                $buffer .= $indent . '        <div class="text-center pt-15" style="clear: both;">
';
                $buffer .= $indent . '          <a href="';
                $value = $this->resolveValue($context->find('user_profiles'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="uppercase">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionE023611d899a4af9cc4668ab37d3367b($context, $indent, $value);
                $buffer .= '</a>
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
