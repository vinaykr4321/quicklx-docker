<?php

class __Mustache_dcf19fbffac14ccc4e2486b6287afe2c extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Footer -->
';
        $buffer .= $indent . '<footer class="site-footer bg-primary-600 grey-100">
';
        // 'footerdata' section
        $value = $context->find('footerdata');
        $buffer .= $this->section637874dac55d9673fcec663f080e88b3($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->findDot('output.standard_end_of_body_html'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '</footer>';

        return $buffer;
    }

    private function section0a6d3d42cba70a91350df4af85c69ddf(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="{{ classes }} text-xs-center">
                <div class="py-30 px-10">
                    <h4 class="card-title mt-10 grey-100">{{ title }}</h4>
                    <p class="card-text">{{{ content }}}</p>
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
                
                $buffer .= $indent . '            <div class="';
                $value = $this->resolveValue($context->find('classes'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' text-xs-center">
';
                $buffer .= $indent . '                <div class="py-30 px-10">
';
                $buffer .= $indent . '                    <h4 class="card-title mt-10 grey-100">';
                $value = $this->resolveValue($context->find('title'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</h4>
';
                $buffer .= $indent . '                    <p class="card-text">';
                $value = $this->resolveValue($context->find('content'), $context);
                $buffer .= $value;
                $buffer .= '</p>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE3fb2f17b1f054f7dd01e69c8c533992(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' followus, theme_remui ';
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
                
                $buffer .= ' followus, theme_remui ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section79a3fc8795b6d604ca2b6020d8766225(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ facebook }}" class="btn btn-icon btn-round social-facebook m-5"><i class="icon fa-facebook" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('facebook'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-facebook m-5"><i class="icon fa-facebook" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2dcdb7c7f5030f62dedfcb919efe5f82(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ twitter }}" class="btn btn-icon btn-round social-twitter m-5"><i class="icon fa-twitter" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('twitter'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-twitter m-5"><i class="icon fa-twitter" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0ce1414c101947cf7cbaf05be4dce6e4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ linkedin }}" class="btn btn-icon btn-round social-linkedin m-5"><i class="icon fa-linkedin" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('linkedin'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-linkedin m-5"><i class="icon fa-linkedin" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDc6edae0890bbca7366e32681aa939c1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ gplus }}" class="btn btn-icon btn-round social-google-plus m-5"><i class="icon fa-google-plus" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('gplus'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-google-plus m-5"><i class="icon fa-google-plus" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDfdbab3309b3b53170658336cd7bd235(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ instagram }}" class="btn btn-icon btn-round social-instagram m-5"><i class="icon fa-instagram" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('instagram'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-instagram m-5"><i class="icon fa-instagram" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section47361538e7b0d7107e43e7827c6f6ffc(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ youtube }}" class="btn btn-icon btn-round social-youtube m-5"><i class="icon fa-youtube" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('youtube'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-youtube m-5"><i class="icon fa-youtube" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB44f22374ad7c22a2ef9b27aeeb2b496(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <a href="{{ pinterest }}" class="btn btn-icon btn-round social-pinterest m-5"><i class="icon fa-pinterest" aria-hidden="true"></i></a>
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
                
                $buffer .= $indent . '                            <a href="';
                $value = $this->resolveValue($context->find('pinterest'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="btn btn-icon btn-round social-pinterest m-5"><i class="icon fa-pinterest" aria-hidden="true"></i></a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8ac1b729863be744c3db1543de4b4d62(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="{{ classes }} text-xs-center">
                <div class="py-30 px-10">
                    <h4 class="card-title mt-10 grey-100">{{# str }} followus, theme_remui {{/ str }}</h4>
                    <p class="card-text">
                        {{# facebook }}
                            <a href="{{ facebook }}" class="btn btn-icon btn-round social-facebook m-5"><i class="icon fa-facebook" aria-hidden="true"></i></a>
                        {{/ facebook }}
                        {{# twitter }}
                            <a href="{{ twitter }}" class="btn btn-icon btn-round social-twitter m-5"><i class="icon fa-twitter" aria-hidden="true"></i></a>
                        {{/ twitter }}
                        {{# linkedin }}
                            <a href="{{ linkedin }}" class="btn btn-icon btn-round social-linkedin m-5"><i class="icon fa-linkedin" aria-hidden="true"></i></a>
                        {{/ linkedin }}
                        {{# gplus }}
                            <a href="{{ gplus }}" class="btn btn-icon btn-round social-google-plus m-5"><i class="icon fa-google-plus" aria-hidden="true"></i></a>
                        {{/ gplus }}
                        {{# instagram }}
                            <a href="{{ instagram }}" class="btn btn-icon btn-round social-instagram m-5"><i class="icon fa-instagram" aria-hidden="true"></i></a>
                        {{/ instagram }}
                        {{# youtube }}
                            <a href="{{ youtube }}" class="btn btn-icon btn-round social-youtube m-5"><i class="icon fa-youtube" aria-hidden="true"></i></a>
                        {{/ youtube }}
                        {{# pinterest }}
                            <a href="{{ pinterest }}" class="btn btn-icon btn-round social-pinterest m-5"><i class="icon fa-pinterest" aria-hidden="true"></i></a>
                        {{/ pinterest }}
                    </p>
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
                
                $buffer .= $indent . '            <div class="';
                $value = $this->resolveValue($context->find('classes'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' text-xs-center">
';
                $buffer .= $indent . '                <div class="py-30 px-10">
';
                $buffer .= $indent . '                    <h4 class="card-title mt-10 grey-100">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionE3fb2f17b1f054f7dd01e69c8c533992($context, $indent, $value);
                $buffer .= '</h4>
';
                $buffer .= $indent . '                    <p class="card-text">
';
                // 'facebook' section
                $value = $context->find('facebook');
                $buffer .= $this->section79a3fc8795b6d604ca2b6020d8766225($context, $indent, $value);
                // 'twitter' section
                $value = $context->find('twitter');
                $buffer .= $this->section2dcdb7c7f5030f62dedfcb919efe5f82($context, $indent, $value);
                // 'linkedin' section
                $value = $context->find('linkedin');
                $buffer .= $this->section0ce1414c101947cf7cbaf05be4dce6e4($context, $indent, $value);
                // 'gplus' section
                $value = $context->find('gplus');
                $buffer .= $this->sectionDc6edae0890bbca7366e32681aa939c1($context, $indent, $value);
                // 'instagram' section
                $value = $context->find('instagram');
                $buffer .= $this->sectionDfdbab3309b3b53170658336cd7bd235($context, $indent, $value);
                // 'youtube' section
                $value = $context->find('youtube');
                $buffer .= $this->section47361538e7b0d7107e43e7827c6f6ffc($context, $indent, $value);
                // 'pinterest' section
                $value = $context->find('pinterest');
                $buffer .= $this->sectionB44f22374ad7c22a2ef9b27aeeb2b496($context, $indent, $value);
                $buffer .= $indent . '                    </p>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2143849f7ac2ba7f01ca7cf1e800ff02(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' poweredby, theme_remui ';
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
                
                $buffer .= ' poweredby, theme_remui ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7cdc395d881fbbbdbac8e4caeab78935(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <a href="https://edwiser.org/remui/" rel="nofollow" target="_blank" >{{# str }} poweredby, theme_remui {{/ str }}</a>
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
                
                $buffer .= $indent . '            <a href="https://edwiser.org/remui/" rel="nofollow" target="_blank" >';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section2143849f7ac2ba7f01ca7cf1e800ff02($context, $indent, $value);
                $buffer .= '</a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section637874dac55d9673fcec663f080e88b3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="container">
        <div class="row">
           {{# sections }}
            <div class="{{ classes }} text-xs-center">
                <div class="py-30 px-10">
                    <h4 class="card-title mt-10 grey-100">{{ title }}</h4>
                    <p class="card-text">{{{ content }}}</p>
                </div>
            </div>
            {{/ sections }}

            {{# social }}
            <div class="{{ classes }} text-xs-center">
                <div class="py-30 px-10">
                    <h4 class="card-title mt-10 grey-100">{{# str }} followus, theme_remui {{/ str }}</h4>
                    <p class="card-text">
                        {{# facebook }}
                            <a href="{{ facebook }}" class="btn btn-icon btn-round social-facebook m-5"><i class="icon fa-facebook" aria-hidden="true"></i></a>
                        {{/ facebook }}
                        {{# twitter }}
                            <a href="{{ twitter }}" class="btn btn-icon btn-round social-twitter m-5"><i class="icon fa-twitter" aria-hidden="true"></i></a>
                        {{/ twitter }}
                        {{# linkedin }}
                            <a href="{{ linkedin }}" class="btn btn-icon btn-round social-linkedin m-5"><i class="icon fa-linkedin" aria-hidden="true"></i></a>
                        {{/ linkedin }}
                        {{# gplus }}
                            <a href="{{ gplus }}" class="btn btn-icon btn-round social-google-plus m-5"><i class="icon fa-google-plus" aria-hidden="true"></i></a>
                        {{/ gplus }}
                        {{# instagram }}
                            <a href="{{ instagram }}" class="btn btn-icon btn-round social-instagram m-5"><i class="icon fa-instagram" aria-hidden="true"></i></a>
                        {{/ instagram }}
                        {{# youtube }}
                            <a href="{{ youtube }}" class="btn btn-icon btn-round social-youtube m-5"><i class="icon fa-youtube" aria-hidden="true"></i></a>
                        {{/ youtube }}
                        {{# pinterest }}
                            <a href="{{ pinterest }}" class="btn btn-icon btn-round social-pinterest m-5"><i class="icon fa-pinterest" aria-hidden="true"></i></a>
                        {{/ pinterest }}
                    </p>
                </div>
            </div>
            {{/ social }}
        </div>
    </div>
    
    <!-- bottom sections -->
    <div class="footer-bottom">
        <div class="site-footer-legal pt-5">
            <a href="{{ bottomlink }}">{{{ bottomtext }}}</a>
        </div>
        
        <div class="site-footer-right pt-5">
            {{# poweredby }}
            <a href="https://edwiser.org/remui/" rel="nofollow" target="_blank" >{{# str }} poweredby, theme_remui {{/ str }}</a>
            {{/ poweredby }}
            
            {{{ output.standard_footer_html }}}
            <div id="course-footer">
                {{{ output.course_footer }}}
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
                
                $buffer .= $indent . '    <div class="container">
';
                $buffer .= $indent . '        <div class="row">
';
                // 'sections' section
                $value = $context->find('sections');
                $buffer .= $this->section0a6d3d42cba70a91350df4af85c69ddf($context, $indent, $value);
                $buffer .= $indent . '
';
                // 'social' section
                $value = $context->find('social');
                $buffer .= $this->section8ac1b729863be744c3db1543de4b4d62($context, $indent, $value);
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '    </div>
';
                $buffer .= $indent . '    
';
                $buffer .= $indent . '    <!-- bottom sections -->
';
                $buffer .= $indent . '    <div class="footer-bottom">
';
                $buffer .= $indent . '        <div class="site-footer-legal pt-5">
';
                $buffer .= $indent . '            <a href="';
                $value = $this->resolveValue($context->find('bottomlink'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('bottomtext'), $context);
                $buffer .= $value;
                $buffer .= '</a>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '        
';
                $buffer .= $indent . '        <div class="site-footer-right pt-5">
';
                // 'poweredby' section
                $value = $context->find('poweredby');
                $buffer .= $this->section7cdc395d881fbbbdbac8e4caeab78935($context, $indent, $value);
                $buffer .= $indent . '            
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->findDot('output.standard_footer_html'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '            <div id="course-footer">
';
                $buffer .= $indent . '                ';
                $value = $this->resolveValue($context->findDot('output.course_footer'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
