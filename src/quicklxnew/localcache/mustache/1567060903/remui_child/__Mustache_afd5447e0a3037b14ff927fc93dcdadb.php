<?php

class __Mustache_afd5447e0a3037b14ff927fc93dcdadb extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- navbar-inverse -->
';
        $buffer .= $indent . '<nav class="site-navbar navbar navbar-default navbar-fixed-top moodle-has-zindex ';
        $value = $this->resolveValue($context->find('navbarinverse'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' ';
        // 'sitecolor' section
        $value = $context->find('sitecolor');
        $buffer .= $this->section04471ede18e4ed26602ca54c4f6a2077($context, $indent, $value);
        $buffer .= '" role="navigation">
';
        $buffer .= $indent . '    <div class="navbar-header d-flex justify-content-end">
';
        $buffer .= $indent . '      <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided mr-auto"
';
        $buffer .= $indent . '      data-toggle="menubar">
';
        $buffer .= $indent . '        <span class="sr-only">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section625e5ef2b01a2fe0f9381fa947e848ac($context, $indent, $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '        <span class="hamburger-bar"></span>
';
        $buffer .= $indent . '      </button>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <div class="navbar-brand navbar-brand-center p-0">
';
        // 'output.should_display_logo' section
        $value = $context->findDot('output.should_display_logo');
        $buffer .= $this->section815200293cf6ba007210979510c585c5($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      ';
        $value = $this->resolveValue($context->findDot('output.search_box_icon_collapsed'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
';
        $buffer .= $indent . '      data-toggle="collapse">
';
        $buffer .= $indent . '        <i class="icon wb-more-horizontal" aria-hidden="true"></i>
';
        $buffer .= $indent . '      </button>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <div class="navbar-container container-fluid" style="-ms-flex:1 1 0%;" >
';
        $buffer .= $indent . '      <!-- Navbar Collapse -->
';
        $buffer .= $indent . '      <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
';
        $buffer .= $indent . '        <!-- Navbar Toolbar -->
';
        $buffer .= $indent . '        
';
        $buffer .= $indent . '        <!-- End Navbar Toolbar -->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <!-- Navbar Toolbar Right -->
';
        $buffer .= $indent . '        <!-- user_menu -->
';
        $buffer .= $indent . '        <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
';
        $buffer .= $indent . '          ';
        $value = $this->resolveValue($context->findDot('output.search_box_icon'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '          
';
        $buffer .= $indent . '          <!-- navbar_plugin_output : \'message\', \'notifications\' and \'chat sidebar\' toggles -->
';
        $buffer .= $indent . '          ';
        $value = $this->resolveValue($context->findDot('output.navbar_plugin_output'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '          
';
        $buffer .= $indent . '          ';
        $value = $this->resolveValue($context->findDot('output.user_menu'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '
';
        // 'cansignup' section
        $value = $context->find('cansignup');
        $buffer .= $this->section97222cb976cf6e8fa7a8e5f0074aac52($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '          ';
        $value = $this->resolveValue($context->findDot('output.navbar_plugin_output_custom_icons'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '        <!-- End Navbar Toolbar Right -->
';
        $buffer .= $indent . '      </div>
';
        $buffer .= $indent . '      <!-- End Navbar Collapse -->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Site Navbar Seach -->
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->findDot('output.search_box'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '      <!-- End Site Navbar Seach -->
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  </nav>
';
        $buffer .= $indent . '  ';
        $value = $this->resolveValue($context->findDot('output.showLicenseNotice'), $context);
        $buffer .= $value;
        $buffer .= '
';

        return $buffer;
    }

    private function section04471ede18e4ed26602ca54c4f6a2077(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' bg-{{sitecolor}}-600 ';
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
                
                $buffer .= ' bg-';
                $value = $this->resolveValue($context->find('sitecolor'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '-600 ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section625e5ef2b01a2fe0f9381fa947e848ac(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'expand, core';
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
                
                $buffer .= 'expand, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDa287094b505aeb29cab423826994853(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <input type="hidden" class="linkcolor" value="{{linkcolor}}">
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
                
                $buffer .= $indent . '        <input type="hidden" class="linkcolor" value="';
                $value = $this->resolveValue($context->find('linkcolor'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section61272ba28702aabb47bf47b698a98026(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
       <input type="hidden" class="maincolor" value="{{maincolor}}">
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
                
                $buffer .= $indent . '       <input type="hidden" class="maincolor" value="';
                $value = $this->resolveValue($context->find('maincolor'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC8291b89a15ef6c2d0d0757c5ae7420a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
       <input type="hidden" class="headingcolor" value="{{headingcolor}}">
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
                
                $buffer .= $indent . '       <input type="hidden" class="headingcolor" value="';
                $value = $this->resolveValue($context->find('headingcolor'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4c740686a655b5cbb88234b1eac2320f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' style="background-color: {{headingcolor}};" ';
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
                
                $buffer .= ' style="background-color: ';
                $value = $this->resolveValue($context->find('headingcolor'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ';" ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionAbffd92b9bff5c069dee8c89d8f41753(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                  <img src="{{newlogo}}" height="25" >
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
                
                $buffer .= $indent . '                  <img src="';
                $value = $this->resolveValue($context->find('newlogo'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" height="25" >
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1006404e01c86a5efee572c44f8bbb1a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                  <img src="{{newlogo}}">
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
                
                $buffer .= $indent . '                  <img src="';
                $value = $this->resolveValue($context->find('newlogo'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section174167512c40ac999fe47dea984158f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{! converted anchor tag to div and anchor tag had href=customurl }}
            <div class="text-white text-center d-block h-full" style="line-height:66px;">
        <input type="hidden" class="newlogo" value="{{newlogo}}">
            {{# linkcolor}}
        <input type="hidden" class="linkcolor" value="{{linkcolor}}">
      {{/ linkcolor}}
      {{# maincolor}}
       <input type="hidden" class="maincolor" value="{{maincolor}}">
      {{/ maincolor}}
      {{# headingcolor}}
       <input type="hidden" class="headingcolor" value="{{headingcolor}}">
      {{/ headingcolor}}
              <span class="navbar-brand-logo mini w-full h-full"  {{# headingcolor}} style="background-color: {{headingcolor}};" {{/ headingcolor}}>
                {{# newlogo}}
                  <img src="{{newlogo}}" height="25" >
                {{/ newlogo}}
                {{^ newlogo}}
                    <i class="fa fa-{{siteicon}}"></i>
                {{/ newlogo}}
              </span>
              <span class="navbar-brand-logo w-full h-full"  {{# headingcolor}} style="background-color: {{headingcolor}};" {{/ headingcolor}}>
                {{# newlogo}}
                  <img src="{{newlogo}}">
                {{/ newlogo}}
                {{^ newlogo}}
                  <i class="fa fa-{{siteicon}}"></i>
                  {{{  }}}
                {{/ newlogo}}
              </span>
            {{! converted anchor tag to div }}
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
                
                $buffer .= $indent . '            <div class="text-white text-center d-block h-full" style="line-height:66px;">
';
                $buffer .= $indent . '        <input type="hidden" class="newlogo" value="';
                $value = $this->resolveValue($context->find('newlogo'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                // 'linkcolor' section
                $value = $context->find('linkcolor');
                $buffer .= $this->sectionDa287094b505aeb29cab423826994853($context, $indent, $value);
                // 'maincolor' section
                $value = $context->find('maincolor');
                $buffer .= $this->section61272ba28702aabb47bf47b698a98026($context, $indent, $value);
                // 'headingcolor' section
                $value = $context->find('headingcolor');
                $buffer .= $this->sectionC8291b89a15ef6c2d0d0757c5ae7420a($context, $indent, $value);
                $buffer .= $indent . '              <span class="navbar-brand-logo mini w-full h-full"  ';
                // 'headingcolor' section
                $value = $context->find('headingcolor');
                $buffer .= $this->section4c740686a655b5cbb88234b1eac2320f($context, $indent, $value);
                $buffer .= '>
';
                // 'newlogo' section
                $value = $context->find('newlogo');
                $buffer .= $this->sectionAbffd92b9bff5c069dee8c89d8f41753($context, $indent, $value);
                // 'newlogo' inverted section
                $value = $context->find('newlogo');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                    <i class="fa fa-';
                    $value = $this->resolveValue($context->find('siteicon'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '"></i>
';
                }
                $buffer .= $indent . '              </span>
';
                $buffer .= $indent . '              <span class="navbar-brand-logo w-full h-full"  ';
                // 'headingcolor' section
                $value = $context->find('headingcolor');
                $buffer .= $this->section4c740686a655b5cbb88234b1eac2320f($context, $indent, $value);
                $buffer .= '>
';
                // 'newlogo' section
                $value = $context->find('newlogo');
                $buffer .= $this->section1006404e01c86a5efee572c44f8bbb1a($context, $indent, $value);
                // 'newlogo' inverted section
                $value = $context->find('newlogo');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                  <i class="fa fa-';
                    $value = $this->resolveValue($context->find('siteicon'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '"></i>
';
                    $buffer .= $indent . '                  ';
                    $value = $this->resolveValue($context->find(''), $context);
                    $buffer .= $value;
                    $buffer .= '
';
                }
                $buffer .= $indent . '              </span>
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC249cbc5780288809fac4c521bb5ff9f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <a class="text-white text-center d-block h-full" href="{{{ config.wwwroot }}}">
              <span class="navbar-brand-logo mini h-full w-full" style="background-image: url({{ logominiurl }});
                    background-position: center; background-size: contain; background-repeat: no-repeat;"></span>

              <span class="navbar-brand-logo h-full w-full" style="background-image: url({{ logourl }});
                    background-position: center; background-size: contain; background-repeat: no-repeat;">
              </span>
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
                
                $buffer .= $indent . '            <a class="text-white text-center d-block h-full" href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="navbar-brand-logo mini h-full w-full" style="background-image: url(';
                $value = $this->resolveValue($context->find('logominiurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ');
';
                $buffer .= $indent . '                    background-position: center; background-size: contain; background-repeat: no-repeat;"></span>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '              <span class="navbar-brand-logo h-full w-full" style="background-image: url(';
                $value = $this->resolveValue($context->find('logourl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ');
';
                $buffer .= $indent . '                    background-position: center; background-size: contain; background-repeat: no-repeat;">
';
                $buffer .= $indent . '              </span>
';
                $buffer .= $indent . '            </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section815200293cf6ba007210979510c585c5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
          {{# isiconsitename }}
            {{! converted anchor tag to div and anchor tag had href=customurl }}
            <div class="text-white text-center d-block h-full" style="line-height:66px;">
        <input type="hidden" class="newlogo" value="{{newlogo}}">
            {{# linkcolor}}
        <input type="hidden" class="linkcolor" value="{{linkcolor}}">
      {{/ linkcolor}}
      {{# maincolor}}
       <input type="hidden" class="maincolor" value="{{maincolor}}">
      {{/ maincolor}}
      {{# headingcolor}}
       <input type="hidden" class="headingcolor" value="{{headingcolor}}">
      {{/ headingcolor}}
              <span class="navbar-brand-logo mini w-full h-full"  {{# headingcolor}} style="background-color: {{headingcolor}};" {{/ headingcolor}}>
                {{# newlogo}}
                  <img src="{{newlogo}}" height="25" >
                {{/ newlogo}}
                {{^ newlogo}}
                    <i class="fa fa-{{siteicon}}"></i>
                {{/ newlogo}}
              </span>
              <span class="navbar-brand-logo w-full h-full"  {{# headingcolor}} style="background-color: {{headingcolor}};" {{/ headingcolor}}>
                {{# newlogo}}
                  <img src="{{newlogo}}">
                {{/ newlogo}}
                {{^ newlogo}}
                  <i class="fa fa-{{siteicon}}"></i>
                  {{{  }}}
                {{/ newlogo}}
              </span>
            {{! converted anchor tag to div }}
            </div>
          {{/ isiconsitename }}
          
          {{# islogo }}
            <a class="text-white text-center d-block h-full" href="{{{ config.wwwroot }}}">
              <span class="navbar-brand-logo mini h-full w-full" style="background-image: url({{ logominiurl }});
                    background-position: center; background-size: contain; background-repeat: no-repeat;"></span>

              <span class="navbar-brand-logo h-full w-full" style="background-image: url({{ logourl }});
                    background-position: center; background-size: contain; background-repeat: no-repeat;">
              </span>
            </a>
          {{/ islogo }}
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
                
                // 'isiconsitename' section
                $value = $context->find('isiconsitename');
                $buffer .= $this->section174167512c40ac999fe47dea984158f7($context, $indent, $value);
                $buffer .= $indent . '          
';
                // 'islogo' section
                $value = $context->find('islogo');
                $buffer .= $this->sectionC249cbc5780288809fac4c521bb5ff9f($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section97222cb976cf6e8fa7a8e5f0074aac52(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
       
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
                
                $buffer .= $indent . '       
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
