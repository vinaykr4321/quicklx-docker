<?php

class __Mustache_abb0c446fe1e147438d8f74421030481 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $value = $this->resolveValue($context->findDot('output.doctype'), $context);
        $buffer .= $indent . $value;
        $buffer .= '
';
        $buffer .= $indent . '<html ';
        $value = $this->resolveValue($context->findDot('output.htmlattributes'), $context);
        $buffer .= $value;
        $buffer .= '>
';
        $buffer .= $indent . '<head>
';
        $buffer .= $indent . '    <title>';
        $value = $this->resolveValue($context->findDot('output.page_title'), $context);
        $buffer .= $value;
        $buffer .= '</title>
';
        $buffer .= $indent . '    <link rel="shortcut icon" href="';
        $value = $this->resolveValue($context->findDot('output.favicon'), $context);
        $buffer .= $value;
        $buffer .= '" />
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->findDot('output.standard_head_html'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    <meta charset="utf-8">
';
        $buffer .= $indent . '    <meta http-equiv="X-UA-Compatible" content="IE=edge">
';
        $buffer .= $indent . '    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<body ';
        $value = $this->resolveValue($context->find('bodyattributes'), $context);
        $buffer .= $value;
        $buffer .= ' data-isfolded="';
        $value = $this->resolveValue($context->find('isfolded'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->findDot('output.standard_top_of_body_html'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '
';
        if ($partial = $this->mustache->loadPartial('theme_remui/header')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        if ($partial = $this->mustache->loadPartial('theme_remui/sidebar')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="page">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '         ';
        $value = $this->resolveValue($context->findDot('output.render_site_announcement'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '         
';
        $buffer .= $indent . '        <!-- blocks aside -->
';
        // 'hasblocks' section
        $value = $context->find('hasblocks');
        $buffer .= $this->section41cb087df10030575f8002847d2791f1($context, $indent, $value);
        $buffer .= $indent . '        <!-- end blocks aside -->';

        return $buffer;
    }

    private function section41cb087df10030575f8002847d2791f1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="page-aside-switch-lg hidden-md-down">
            <i class="wb-chevron-left" aria-hidden="true"></i>
            <i class="wb-chevron-right" aria-hidden="true"></i>
        </div>
        <div class="page-aside d-0">
            <div class="page-aside-switch">
                <i class="wb-chevron-left" aria-hidden="true"></i>
                <i class="wb-chevron-right" aria-hidden="true"></i>
            </div>
            <div class="page-aside-inner page-aside-scroll">
                <div data-role="container">
                    <div data-role="content">
                        {{{ sidepreblocks }}}
                    </div>
                </div>
            </div>
            <!---page-aside-inner-->
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
                
                $buffer .= $indent . '        <div class="page-aside-switch-lg hidden-md-down">
';
                $buffer .= $indent . '            <i class="wb-chevron-left" aria-hidden="true"></i>
';
                $buffer .= $indent . '            <i class="wb-chevron-right" aria-hidden="true"></i>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '        <div class="page-aside d-0">
';
                $buffer .= $indent . '            <div class="page-aside-switch">
';
                $buffer .= $indent . '                <i class="wb-chevron-left" aria-hidden="true"></i>
';
                $buffer .= $indent . '                <i class="wb-chevron-right" aria-hidden="true"></i>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            <div class="page-aside-inner page-aside-scroll">
';
                $buffer .= $indent . '                <div data-role="container">
';
                $buffer .= $indent . '                    <div data-role="content">
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('sidepreblocks'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            <!---page-aside-inner-->
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
