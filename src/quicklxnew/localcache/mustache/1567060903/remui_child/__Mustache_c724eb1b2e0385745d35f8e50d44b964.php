<?php

class __Mustache_c724eb1b2e0385745d35f8e50d44b964 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '    <!-- End Page -->
';
        $buffer .= $indent . '
';
        if ($partial = $this->mustache->loadPartial('theme_remui/footer')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>';

        return $buffer;
    }
}
