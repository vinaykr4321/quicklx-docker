<?php

class __Mustache_82b196a190690369bc1fb7486055eeb5 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="form-group ';
        // 'error' section
        $value = $context->find('error');
        $buffer .= $this->section4d4b829e8a762a460d7710f0a2273a46($context, $indent, $value);
        $buffer .= ' fitem ';
        // 'advanced' section
        $value = $context->find('advanced');
        $buffer .= $this->sectionB2a3879159edb3a7a33a1d8394a2c556($context, $indent, $value);
        $buffer .= ' ';
        $value = $this->resolveValue($context->findDot('element.extraclasses'), $context);
        $buffer .= $value;
        $buffer .= '">
';
        $buffer .= $indent . '    <label class="col-form-label ';
        // 'element.hiddenlabel' section
        $value = $context->findDot('element.hiddenlabel');
        $buffer .= $this->sectionBd3e0eae730f027ebaa2c5d8144fd11d($context, $indent, $value);
        $buffer .= '" for="';
        $value = $this->resolveValue($context->findDot('element.id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('label'), $context);
        $buffer .= $value;
        $buffer .= ' ';
        $value = $this->resolveValue($context->find('helpbutton'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '        ';
        // 'required' section
        $value = $context->find('required');
        $buffer .= $this->section55a5d13dc046825f02136edbc9072d9a($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        ';
        // 'advanced' section
        $value = $context->find('advanced');
        $buffer .= $this->sectionDff86beb7df6f52055c982374f1eb239($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    </label>
';
        $buffer .= $indent . '    <span data-fieldtype="';
        $value = $this->resolveValue($context->findDot('element.type'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $blockFunction = $context->findInBlock('element');
        if (is_callable($blockFunction)) {
            $buffer .= call_user_func($blockFunction, $context);
        } else {
            $buffer .= $indent . '            <!-- Element goes here -->
';
        }
        $buffer .= $indent . '    </span>
';
        $buffer .= $indent . '    <div class="form-control-feedback" id="id_error_';
        $value = $this->resolveValue($context->findDot('element.name'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" ';
        // 'error' inverted section
        $value = $context->find('error');
        if (empty($value)) {
            
            $buffer .= ' style="display: none;"';
        }
        $buffer .= '>
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('error'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->sectionC0573f7bc977debaccc6c190edc974e8($context, $indent, $value);

        return $buffer;
    }

    private function section4d4b829e8a762a460d7710f0a2273a46(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'has-danger';
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
                
                $buffer .= 'has-danger';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB2a3879159edb3a7a33a1d8394a2c556(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'advanced';
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
                
                $buffer .= 'advanced';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBd3e0eae730f027ebaa2c5d8144fd11d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'sr-only';
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
                
                $buffer .= 'sr-only';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5197bd4c78ccebd47c9f052795fcb4e4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'required';
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
                
                $buffer .= 'required';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1a62044b8b32f10f428bdf36250d9aac(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'req, core, {{#str}}required{{/str}}';
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
                
                $buffer .= 'req, core, ';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section5197bd4c78ccebd47c9f052795fcb4e4($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section55a5d13dc046825f02136edbc9072d9a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<abbr class="initialism text-danger" title="{{#str}}required{{/str}}">{{#pix}}req, core, {{#str}}required{{/str}}{{/pix}}</abbr>';
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
                
                $buffer .= '<abbr class="initialism text-danger" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section5197bd4c78ccebd47c9f052795fcb4e4($context, $indent, $value);
                $buffer .= '">';
                // 'pix' section
                $value = $context->find('pix');
                $buffer .= $this->section1a62044b8b32f10f428bdf36250d9aac($context, $indent, $value);
                $buffer .= '</abbr>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDff86beb7df6f52055c982374f1eb239(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<abbr class="initialism text-info" title="{{#str}}advanced{{/str}}">!</abbr>';
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
                
                $buffer .= '<abbr class="initialism text-info" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB2a3879159edb3a7a33a1d8394a2c556($context, $indent, $value);
                $buffer .= '">!</abbr>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD51d59cf174abfd59abe29508608fc20(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{element.id}}';
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
                
                $value = $this->resolveValue($context->findDot('element.id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC0573f7bc977debaccc6c190edc974e8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'theme_remui/form-display-errors\'], function(module) {
    module.enhance({{#quote}}{{element.id}}{{/quote}});
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
                
                $buffer .= $indent . 'require([\'theme_remui/form-display-errors\'], function(module) {
';
                $buffer .= $indent . '    module.enhance(';
                // 'quote' section
                $value = $context->find('quote');
                $buffer .= $this->sectionD51d59cf174abfd59abe29508608fc20($context, $indent, $value);
                $buffer .= ');
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
