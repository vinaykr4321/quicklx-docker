<?php

class __Mustache_3af19fd3517229a4b0e5afe8ac5c694f extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '   
';
        // 'flatnavigation' section
        $value = $context->find('flatnavigation');
        $buffer .= $this->sectionE4785f5ab0f408d5d605b00eab6c7ada($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';

        return $buffer;
    }

    private function section20a71f2444e83e2d7054bffe2061d26f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
</li>

<li class="site-menu-category"></li>
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
                
                $buffer .= $indent . '</li>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '<li class="site-menu-category"></li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5749c750acb0d7477dd5257d00cc6d53(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'active';
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
                
                $buffer .= 'active';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4d63c27927abdcdd436fab5a7cbff9f2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'hidable collapse';
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
                
                $buffer .= 'hidable collapse';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3f9a6459a7cc59b2b62b7b31e7339385(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <li class="site-menu-item pl-20 {{#isactive}}active{{/isactive}} {{#hidable}}hidable collapse{{/hidable}}">
            <a href="{{{action}}}" data-key="{{key}}" class="py-5">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
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
                
                $buffer .= $indent . '            <li class="site-menu-item pl-20 ';
                // 'isactive' section
                $value = $context->find('isactive');
                $buffer .= $this->section5749c750acb0d7477dd5257d00cc6d53($context, $indent, $value);
                $buffer .= ' ';
                // 'hidable' section
                $value = $context->find('hidable');
                $buffer .= $this->section4d63c27927abdcdd436fab5a7cbff9f2($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->find('action'), $context);
                $buffer .= $value;
                $buffer .= '" data-key="';
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="py-5">
';
                $buffer .= $indent . '                <i class="site-menu-icon ';
                $value = $this->resolveValue($context->find('remuiicon'), $context);
                $buffer .= $value;
                $buffer .= '" aria-hidden="true"></i>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionF7f5eb5a1a2802e3891ac33aebe21de8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'toggler ';
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
                
                $buffer .= 'toggler ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2a2327591b84d9434bc927ab8d064113(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'pr-0 ';
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
                
                $buffer .= 'pr-0 ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section313529b22e73c12ff7d8a348c6a99530(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <span class="site-menu-toggle float-right pl-20"><i class="site-menu-icon {{{toggleicon}}}" aria-hidden="true"></i></span>
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
                
                $buffer .= $indent . '                    <span class="site-menu-toggle float-right pl-20"><i class="site-menu-icon ';
                $value = $this->resolveValue($context->find('toggleicon'), $context);
                $buffer .= $value;
                $buffer .= '" aria-hidden="true"></i></span>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0f4aa91a8b697f3f394462109e61da35(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{#get_indent}}
            <li class="site-menu-item pl-20 {{#isactive}}active{{/isactive}} {{#hidable}}hidable collapse{{/hidable}}">
            <a href="{{{action}}}" data-key="{{key}}" class="py-5">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
        {{/get_indent}}
        {{^get_indent}}
            <li class="site-menu-item {{#togglable}}toggler {{/togglable}} {{#isactive}}active{{/isactive}}">
            <a href="{{{action}}}" data-key="{{key}}" class="py-5 {{#togglable}}pr-0 {{/togglable}}">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
        {{/get_indent}}
                <span class="site-menu-title">{{{text}}}</span>
                {{#togglable}}
                    <span class="site-menu-toggle float-right pl-20"><i class="site-menu-icon {{{toggleicon}}}" aria-hidden="true"></i></span>
                {{/togglable}}
            </a>
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
                
                // 'get_indent' section
                $value = $context->find('get_indent');
                $buffer .= $this->section3f9a6459a7cc59b2b62b7b31e7339385($context, $indent, $value);
                // 'get_indent' inverted section
                $value = $context->find('get_indent');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            <li class="site-menu-item ';
                    // 'togglable' section
                    $value = $context->find('togglable');
                    $buffer .= $this->sectionF7f5eb5a1a2802e3891ac33aebe21de8($context, $indent, $value);
                    $buffer .= ' ';
                    // 'isactive' section
                    $value = $context->find('isactive');
                    $buffer .= $this->section5749c750acb0d7477dd5257d00cc6d53($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '            <a href="';
                    $value = $this->resolveValue($context->find('action'), $context);
                    $buffer .= $value;
                    $buffer .= '" data-key="';
                    $value = $this->resolveValue($context->find('key'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '" class="py-5 ';
                    // 'togglable' section
                    $value = $context->find('togglable');
                    $buffer .= $this->section2a2327591b84d9434bc927ab8d064113($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '                <i class="site-menu-icon ';
                    $value = $this->resolveValue($context->find('remuiicon'), $context);
                    $buffer .= $value;
                    $buffer .= '" aria-hidden="true"></i>
';
                }
                $buffer .= $indent . '                <span class="site-menu-title">';
                $value = $this->resolveValue($context->find('text'), $context);
                $buffer .= $value;
                $buffer .= '</span>
';
                // 'togglable' section
                $value = $context->find('togglable');
                $buffer .= $this->section313529b22e73c12ff7d8a348c6a99530($context, $indent, $value);
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '        </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5b3d415a4c0172c2839c4a1050f805f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <li class="site-menu-item pl-20 {{#isactive}}active{{/isactive}}">
            <a href="javascript:void(0)" data-key="{{key}}" class="py-5">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
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
                
                $buffer .= $indent . '            <li class="site-menu-item pl-20 ';
                // 'isactive' section
                $value = $context->find('isactive');
                $buffer .= $this->section5749c750acb0d7477dd5257d00cc6d53($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '            <a href="javascript:void(0)" data-key="';
                $value = $this->resolveValue($context->find('key'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="py-5">
';
                $buffer .= $indent . '                <i class="site-menu-icon ';
                $value = $this->resolveValue($context->find('remuiicon'), $context);
                $buffer .= $value;
                $buffer .= '" aria-hidden="true"></i>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE4785f5ab0f408d5d605b00eab6c7ada(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    {{#showdivider}}
</li>

<li class="site-menu-category"></li>
    {{/showdivider}}
    {{#action}}
        {{#get_indent}}
            <li class="site-menu-item pl-20 {{#isactive}}active{{/isactive}} {{#hidable}}hidable collapse{{/hidable}}">
            <a href="{{{action}}}" data-key="{{key}}" class="py-5">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
        {{/get_indent}}
        {{^get_indent}}
            <li class="site-menu-item {{#togglable}}toggler {{/togglable}} {{#isactive}}active{{/isactive}}">
            <a href="{{{action}}}" data-key="{{key}}" class="py-5 {{#togglable}}pr-0 {{/togglable}}">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
        {{/get_indent}}
                <span class="site-menu-title">{{{text}}}</span>
                {{#togglable}}
                    <span class="site-menu-toggle float-right pl-20"><i class="site-menu-icon {{{toggleicon}}}" aria-hidden="true"></i></span>
                {{/togglable}}
            </a>
        </li>
    {{/action}}
    {{^action}}
        {{#get_indent}}
            <li class="site-menu-item pl-20 {{#isactive}}active{{/isactive}}">
            <a href="javascript:void(0)" data-key="{{key}}" class="py-5">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
        {{/get_indent}}
        {{^get_indent}}
            <li class="site-menu-item {{#isactive}}active{{/isactive}}">
            <a href="javascript:void(0)" data-key="{{key}}" class="py-5">
                <i class="site-menu-icon {{{remuiicon}}}" aria-hidden="true"></i>
        {{/get_indent}}
                <span class="site-menu-title">{{{text}}}</span>
            </a>
        </li>
    {{/action}}


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
                
                // 'showdivider' section
                $value = $context->find('showdivider');
                $buffer .= $this->section20a71f2444e83e2d7054bffe2061d26f($context, $indent, $value);
                // 'action' section
                $value = $context->find('action');
                $buffer .= $this->section0f4aa91a8b697f3f394462109e61da35($context, $indent, $value);
                // 'action' inverted section
                $value = $context->find('action');
                if (empty($value)) {
                    
                    // 'get_indent' section
                    $value = $context->find('get_indent');
                    $buffer .= $this->section5b3d415a4c0172c2839c4a1050f805f7($context, $indent, $value);
                    // 'get_indent' inverted section
                    $value = $context->find('get_indent');
                    if (empty($value)) {
                        
                        $buffer .= $indent . '            <li class="site-menu-item ';
                        // 'isactive' section
                        $value = $context->find('isactive');
                        $buffer .= $this->section5749c750acb0d7477dd5257d00cc6d53($context, $indent, $value);
                        $buffer .= '">
';
                        $buffer .= $indent . '            <a href="javascript:void(0)" data-key="';
                        $value = $this->resolveValue($context->find('key'), $context);
                        $buffer .= call_user_func($this->mustache->getEscape(), $value);
                        $buffer .= '" class="py-5">
';
                        $buffer .= $indent . '                <i class="site-menu-icon ';
                        $value = $this->resolveValue($context->find('remuiicon'), $context);
                        $buffer .= $value;
                        $buffer .= '" aria-hidden="true"></i>
';
                    }
                    $buffer .= $indent . '                <span class="site-menu-title">';
                    $value = $this->resolveValue($context->find('text'), $context);
                    $buffer .= $value;
                    $buffer .= '</span>
';
                    $buffer .= $indent . '            </a>
';
                    $buffer .= $indent . '        </li>
';
                }
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
