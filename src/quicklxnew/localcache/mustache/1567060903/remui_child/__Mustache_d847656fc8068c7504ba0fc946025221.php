<?php

class __Mustache_d847656fc8068c7504ba0fc946025221 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '   <div class="site-menubar ';
        $value = $this->resolveValue($context->find('sidebarcolor'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' moodle-has-zindex" style="background:';
        $value = $this->resolveValue($context->find('leftcolumncolor'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ';"> 
';
        $buffer .= $indent . '    <div class="site-menubar-body">
';
        $buffer .= $indent . '      <div>
';
        $buffer .= $indent . '        <ul class="site-menu mt-15" data-plugin="menu">
';
        // 'isactivitypage' section
        $value = $context->find('isactivitypage');
        $buffer .= $this->section7baad8b0d5e2b28b6a1775d86c401efa($context, $indent, $value);
        $buffer .= $indent . '          
';
        // 'isactivitypage' inverted section
        $value = $context->find('isactivitypage');
        if (empty($value)) {
            
            if ($partial = $this->mustache->loadPartial('theme_remui/flat_navigation')) {
                $buffer .= $partial->renderInternal($context, $indent . '            ');
            }
        }
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '      </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <div class="site-menubar-footer">
';
        // 'output.check_user_site_admin' section
        $value = $context->findDot('output.check_user_site_admin');
        $buffer .= $this->sectionC40b810e72d41e66fa22147b08cdd673($context, $indent, $value);
        // 'output.check_user_site_admin' inverted section
        $value = $context->findDot('output.check_user_site_admin');
        if (empty($value)) {
            
            // 'output.check_blog_enable' section
            $value = $context->findDot('output.check_blog_enable');
            $buffer .= $this->section9afc824a0e077d901903b8cf31b516e0($context, $indent, $value);
            // 'output.check_blog_enable' inverted section
            $value = $context->findDot('output.check_blog_enable');
            if (empty($value)) {
                
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/course/index.php" class="fold-show w-p100" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section76841790df3d84796d42d78d8defab52($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon wb-book" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
            }
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  </div>';

        return $buffer;
    }

    private function section7baad8b0d5e2b28b6a1775d86c401efa(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{> theme_remui/activity_navigation }}
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
                
                if ($partial = $this->mustache->loadPartial('theme_remui/activity_navigation')) {
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section01b1ec2a32612a397286f43c3d622908(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'siteblog, theme_remui';
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
                
                $buffer .= 'siteblog, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8e8f4fe551b836ec4740ec0d00ce9096(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'createanewcourse, theme_remui';
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
                
                $buffer .= 'createanewcourse, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section76841790df3d84796d42d78d8defab52(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'createarchivepage, theme_remui';
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
                
                $buffer .= 'createarchivepage, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section654e3b674a90879ff1485c9f897ade25(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'remuisettings, theme_remui';
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
                
                $buffer .= 'remuisettings, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section83145ebde016cb46e3463bbdba7a23cf(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <a href="{{config.wwwroot}}/blog/index.php" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}siteblog, theme_remui{{/str}}">
              <span class="icon fa-comments" aria-hidden="true"></span>
            </a>
            <a href="{{ coursecreationlink }}" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createanewcourse, theme_remui{{/str}}">
              <span class="icon wb-file" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/course/index.php" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createarchivepage, theme_remui{{/str}}">
              <span class="icon wb-book" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/admin/settings.php?section=themesettingremui" class="fold-show w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}remuisettings, theme_remui{{/str}}">
              <span class="icon wb-settings" aria-hidden="true"></span>
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
                
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/blog/index.php" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section01b1ec2a32612a397286f43c3d622908($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon fa-comments" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->find('coursecreationlink'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section8e8f4fe551b836ec4740ec0d00ce9096($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon wb-file" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/course/index.php" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section76841790df3d84796d42d78d8defab52($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon wb-book" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/admin/settings.php?section=themesettingremui" class="fold-show w-p25" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section654e3b674a90879ff1485c9f897ade25($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon wb-settings" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC40b810e72d41e66fa22147b08cdd673(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{# output.check_blog_enable }}
            <a href="{{config.wwwroot}}/blog/index.php" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}siteblog, theme_remui{{/str}}">
              <span class="icon fa-comments" aria-hidden="true"></span>
            </a>
            <a href="{{ coursecreationlink }}" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createanewcourse, theme_remui{{/str}}">
              <span class="icon wb-file" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/course/index.php" class="w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createarchivepage, theme_remui{{/str}}">
              <span class="icon wb-book" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/admin/settings.php?section=themesettingremui" class="fold-show w-p25" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}remuisettings, theme_remui{{/str}}">
              <span class="icon wb-settings" aria-hidden="true"></span>
            </a>
        {{/ output.check_blog_enable }}
        {{^ output.check_blog_enable }}
            <a href="{{ coursecreationlink }}" class="col-4" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createanewcourse, theme_remui{{/str}}">
              <span class="icon wb-file" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/course/index.php" class="col-4" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createarchivepage, theme_remui{{/str}}">
              <span class="icon wb-book" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/admin/settings.php?section=themesettingremui" class="fold-show col-4" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}remuisettings, theme_remui{{/str}}">
              <span class="icon wb-settings" aria-hidden="true"></span>
            </a>
        {{/ output.check_blog_enable }}
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
                
                // 'output.check_blog_enable' section
                $value = $context->findDot('output.check_blog_enable');
                $buffer .= $this->section83145ebde016cb46e3463bbdba7a23cf($context, $indent, $value);
                // 'output.check_blog_enable' inverted section
                $value = $context->findDot('output.check_blog_enable');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            <a href="';
                    $value = $this->resolveValue($context->find('coursecreationlink'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '" class="col-4" data-placement="top" data-toggle="tooltip" data-original-title="';
                    // 'str' section
                    $value = $context->find('str');
                    $buffer .= $this->section8e8f4fe551b836ec4740ec0d00ce9096($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '              <span class="icon wb-file" aria-hidden="true"></span>
';
                    $buffer .= $indent . '            </a>
';
                    $buffer .= $indent . '            <a href="';
                    $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '/course/index.php" class="col-4" data-placement="top" data-toggle="tooltip" data-original-title="';
                    // 'str' section
                    $value = $context->find('str');
                    $buffer .= $this->section76841790df3d84796d42d78d8defab52($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '              <span class="icon wb-book" aria-hidden="true"></span>
';
                    $buffer .= $indent . '            </a>
';
                    $buffer .= $indent . '            <a href="';
                    $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '/admin/settings.php?section=themesettingremui" class="fold-show col-4" data-placement="top" data-toggle="tooltip" data-original-title="';
                    // 'str' section
                    $value = $context->find('str');
                    $buffer .= $this->section654e3b674a90879ff1485c9f897ade25($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '              <span class="icon wb-settings" aria-hidden="true"></span>
';
                    $buffer .= $indent . '            </a>
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9afc824a0e077d901903b8cf31b516e0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <a href="{{config.wwwroot}}/blog/index.php" class="fold-show w-p50" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}siteblog, theme_remui{{/str}}">
              <span class="icon fa-comments" aria-hidden="true"></span>
            </a>
            <a href="{{config.wwwroot}}/course/index.php" class="w-p50" data-placement="top" data-toggle="tooltip" data-original-title="{{#str}}createarchivepage, theme_remui{{/str}}">
              <span class="icon wb-book" aria-hidden="true"></span>
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
                
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/blog/index.php" class="fold-show w-p50" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section01b1ec2a32612a397286f43c3d622908($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon fa-comments" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/course/index.php" class="w-p50" data-placement="top" data-toggle="tooltip" data-original-title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section76841790df3d84796d42d78d8defab52($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="icon wb-book" aria-hidden="true"></span>
';
                $buffer .= $indent . '            </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
