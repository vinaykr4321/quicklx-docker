<?php

class __Mustache_1f9168b49e3bd1306507498a9d0cccd2 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<li class="popover-region collapsed ';
        $blockFunction = $context->findInBlock('classes');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= ' nav-item dropdown"
';
        $buffer .= $indent . '    ';
        $blockFunction = $context->findInBlock('attributes');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '
';
        $buffer .= $indent . '    data-region="popover-region">
';
        $buffer .= $indent . '    <a class="popover-region-toggle nav-link"
';
        $buffer .= $indent . '        data-toggle="dropdown"
';
        $buffer .= $indent . '        data-region="popover-region-toggle"
';
        $buffer .= $indent . '        href="javascript:void(0)"
';
        $buffer .= $indent . '        title="';
        $blockFunction = $context->findInBlock('toggletitle');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '"
';
        $buffer .= $indent . '        aria-expanded="false"
';
        $buffer .= $indent . '        data-animation="scale-up"
';
        $buffer .= $indent . '        role="button">
';
        $buffer .= $indent . '        ';
        $blockFunction = $context->findInBlock('togglecontent');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '
';
        $buffer .= $indent . '    </a>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div ';
        $blockFunction = $context->findInBlock('containerattributes');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '
';
        $buffer .= $indent . '        id="popover-region-container-';
        $value = $this->resolveValue($context->find('uniqid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"
';
        $buffer .= $indent . '        class="dropdown-menu dropdown-menu-right dropdown-menu-media"
';
        $buffer .= $indent . '        style="height: auto;"
';
        $buffer .= $indent . '        data-region="popover-region-container"
';
        $buffer .= $indent . '        aria-expanded="false"
';
        $buffer .= $indent . '        aria-hidden="true"
';
        $buffer .= $indent . '        aria-label="';
        $blockFunction = $context->findInBlock('containerlabel');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '"
';
        $buffer .= $indent . '        role="region">
';
        $buffer .= $indent . '        <div class="dropdown-menu-header">
';
        $buffer .= $indent . '            <h5 class="text-uppercase d-inline" data-region="popover-region-header-text">';
        $blockFunction = $context->findInBlock('headertext');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '</h5>
';
        $buffer .= $indent . '            <span class="float-right popover-region-header-actions">';
        $blockFunction = $context->findInBlock('headeractions');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '</span>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <div class="list-group" role="presentation">
';
        $buffer .= $indent . '            <div class="popover-region-content-container" style="min-height: 200px;" data-role="container" data-region="popover-region-content-container">
';
        $buffer .= $indent . '                <div class="popover-region-content" data-role="content" data-region="popover-region-content">
';
        $buffer .= $indent . '                    ';
        $blockFunction = $context->findInBlock('content');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '
';
        $buffer .= $indent . '                </div>
';
        if ($partial = $this->mustache->loadPartial('core/loading')) {
            $buffer .= $partial->renderInternal($context, $indent . '                ');
        }
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        <div class="dropdown-menu-footer" role="presentation">
';
        $buffer .= $indent . '            ';
        $blockFunction = $context->findInBlock('footeractions');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        }
        $buffer .= '
';
        $buffer .= $indent . '
';
        $blockFunction = $context->findInBlock('anchor');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        } else {
            // 'urls.seeall' section
            $value = $context->findDot('urls.seeall');
            $buffer .= $this->section1e633192369f882ddbff40ac2fb1f871($context, $indent, $value);
        }
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</li>
';

        return $buffer;
    }

    private function section560076495ba24e041d1e004b36f2b0d1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' seeall, message ';
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
                
                $buffer .= ' seeall, message ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1e633192369f882ddbff40ac2fb1f871(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <a class="see-all-link dropdown-item"
                        role="menuitem"
                        href="{{{.}}}">
                        {{#str}} seeall, message {{/str}}
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
                
                $buffer .= $indent . '                    <a class="see-all-link dropdown-item"
';
                $buffer .= $indent . '                        role="menuitem"
';
                $buffer .= $indent . '                        href="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '                        ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section560076495ba24e041d1e004b36f2b0d1($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                    </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
