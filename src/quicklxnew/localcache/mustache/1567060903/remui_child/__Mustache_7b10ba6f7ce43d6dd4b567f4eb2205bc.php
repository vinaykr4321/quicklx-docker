<?php

class __Mustache_7b10ba6f7ce43d6dd4b567f4eb2205bc extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div id="recent-section">
';
        $buffer .= $indent . '    <div class = "w-p100 bg-white">
';
        $buffer .= $indent . '        <ul class="nav nav-tabs nav-tabs-line border-0 p-15" role="tablist">
';
        $buffer .= $indent . '            <li class="nav-item">
';
        $buffer .= $indent . '                <a class="nav-link active" href="#recent_assignment" role="tab" data-toggle="tab">
';
        // 'hasrecentassignments' section
        $value = $context->find('hasrecentassignments');
        $buffer .= $this->section2569cb99db5fa55bc12aaa9e329131de($context, $indent, $value);
        // 'hasrecentassignments' inverted section
        $value = $context->find('hasrecentassignments');
        if (empty($value)) {
            
            $buffer .= $indent . '                        ';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->sectionF31caf4264345fc78da09bcdf80d96e6($context, $indent, $value);
            $buffer .= '
';
        }
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '            </li>
';
        $buffer .= $indent . '            <li class="nav-item">
';
        $buffer .= $indent . '                <a class="nav-link" href="#recent_active_forum" role="tab" data-toggle="tab">
';
        $buffer .= $indent . '                    ';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionB77c30066122395d14844963c8a585e2($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '            </li>
';
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <div class="tab-content bg-white">
';
        $buffer .= $indent . '        <div role="tabpanel" class="tab-pane fade show active" id="recent_assignment">
';
        if ($partial = $this->mustache->loadPartial('theme_remui/recent_assignments')) {
            $buffer .= $partial->renderInternal($context, $indent . '            ');
        }
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        <div role="tabpanel" class="tab-pane fade show" id="recent_active_forum">
';
        if ($partial = $this->mustache->loadPartial('theme_remui/recent_active_forum')) {
            $buffer .= $partial->renderInternal($context, $indent . '            ');
        }
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>';

        return $buffer;
    }

    private function sectionB8e776a60882a6fdbaa8ee76049ff795(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'assignmentstobegraded, theme_remui';
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
                
                $buffer .= 'assignmentstobegraded, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2569cb99db5fa55bc12aaa9e329131de(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        {{#str}}assignmentstobegraded, theme_remui{{/str}}
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
                
                $buffer .= $indent . '                        ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB8e776a60882a6fdbaa8ee76049ff795($context, $indent, $value);
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionF31caf4264345fc78da09bcdf80d96e6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'recentfeedback, theme_remui';
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
                
                $buffer .= 'recentfeedback, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB77c30066122395d14844963c8a585e2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'recentlyactiveforums, theme_remui';
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
                
                $buffer .= 'recentlyactiveforums, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
