<?php

class __Mustache_6fd4e9f1db6aa8c0c981f2ad087bdb5a extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="recent_active_forum" class="col-md-12 p-0">
';
        $buffer .= $indent . '    <div class="card card-shadow mh-100 mx-0 ';
        // 'hasrecentforums' section
        $value = $context->find('hasrecentforums');
        $buffer .= $this->sectionC28692c34e97b9d6ffe5a1a9296f8e4c($context, $indent, $value);
        // 'hasrecentforums' inverted section
        $value = $context->find('hasrecentforums');
        if (empty($value)) {
            
            $buffer .= 'p-20';
        }
        $buffer .= '" style="overflow-y: hidden;">
';
        // 'hasrecentforums' section
        $value = $context->find('hasrecentforums');
        $buffer .= $this->section5c7acc8579c065da40b4029f733200aa($context, $indent, $value);
        // 'hasrecentforums' inverted section
        $value = $context->find('hasrecentforums');
        if (empty($value)) {
            
            $buffer .= $indent . '          ';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->sectionE7afea090a62a3082b453179da012d12($context, $indent, $value);
            $buffer .= '
';
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionC28692c34e97b9d6ffe5a1a9296f8e4c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'px-20 pb-20';
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
                
                $buffer .= 'px-20 pb-20';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section95a75ee8cc740b2b29528243fa7bec5a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'discussion, forum';
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
                
                $buffer .= 'discussion, forum';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section028e961e735b9df9d12ae3ead5122a5e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'activity, moodle';
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
                
                $buffer .= 'activity, moodle';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB6ede2badb1bdaf8351eb6fff9f25b62(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'users, moodle';
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
                
                $buffer .= 'users, moodle';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC513e7f1bd8c373874ab1df75f8a8502(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'replies, forum';
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
                
                $buffer .= 'replies, forum';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section82fa1bb8fbda0b28c313a5dbc2912744(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'lastpost, forum';
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
                
                $buffer .= 'lastpost, forum';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section96b004d1a1dd1f6d140bfe720562dec1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{ config.wwwroot }}/mod/forum/discuss.php?d={{ discussion }}#p{{ id }}';
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
                
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/mod/forum/discuss.php?d=';
                $value = $this->resolveValue($context->find('discussion'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '#p';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section85fcfd7c30df2ef6e939c4065fd05871(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <a href="{{ config.wwwroot }}/user/profile.php?id={{ id }}" style="text-decoration: none;">
                        <img src="{{ profilepicture }}" class="avatar" alt="#">
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
                
                $buffer .= $indent . '                        <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/user/profile.php?id=';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" style="text-decoration: none;">
';
                $buffer .= $indent . '                        <img src="';
                $value = $this->resolveValue($context->find('profilepicture'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="avatar" alt="#">
';
                $buffer .= $indent . '                        </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD4bf969fa7502a5a202d9ef7b5be17f3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{ timestamp }}, %A, %d %B %Y ';
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
                
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('timestamp'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ', %A, %d %B %Y ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section74e777cd27fdd99fa4dc13b19e546e65(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                 <tr class=\'clickable-row\' data-href= \'\'>
                    <td><a href = "{{# content }}{{ config.wwwroot }}/mod/forum/discuss.php?d={{ discussion }}#p{{ id }}{{/ content }}" style="text-decoration: none;">{{ content.subject }}</a></td>
                    <td><a href="{{ config.wwwroot }}/mod/forum/view.php?id={{ cmid }}" style="text-decoration: none;">{{ courseshortname }} / {{ forumname }}</a></td>
                    <td style ="width:20%;">
                    {{# recentuser}}
                        <a href="{{ config.wwwroot }}/user/profile.php?id={{ id }}" style="text-decoration: none;">
                        <img src="{{ profilepicture }}" class="avatar" alt="#">
                        </a>
                    {{/ recentuser}}
                  </td>
                  <td>{{ replies }}</td>
                  <td> <a href="{{ config.wwwroot }}/user/profile.php?id={{ user.id }}" style="text-decoration: none;">{{ user.firstname }} {{ user.lastname }}</a></br>{{#userdate}} {{ timestamp }}, %A, %d %B %Y {{/userdate}}</td>
                </tr>
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
                
                $buffer .= $indent . '                 <tr class=\'clickable-row\' data-href= \'\'>
';
                $buffer .= $indent . '                    <td><a href = "';
                // 'content' section
                $value = $context->find('content');
                $buffer .= $this->section96b004d1a1dd1f6d140bfe720562dec1($context, $indent, $value);
                $buffer .= '" style="text-decoration: none;">';
                $value = $this->resolveValue($context->findDot('content.subject'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></td>
';
                $buffer .= $indent . '                    <td><a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/mod/forum/view.php?id=';
                $value = $this->resolveValue($context->find('cmid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" style="text-decoration: none;">';
                $value = $this->resolveValue($context->find('courseshortname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' / ';
                $value = $this->resolveValue($context->find('forumname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></td>
';
                $buffer .= $indent . '                    <td style ="width:20%;">
';
                // 'recentuser' section
                $value = $context->find('recentuser');
                $buffer .= $this->section85fcfd7c30df2ef6e939c4065fd05871($context, $indent, $value);
                $buffer .= $indent . '                  </td>
';
                $buffer .= $indent . '                  <td>';
                $value = $this->resolveValue($context->find('replies'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</td>
';
                $buffer .= $indent . '                  <td> <a href="';
                $value = $this->resolveValue($context->findDot('config.wwwroot'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/user/profile.php?id=';
                $value = $this->resolveValue($context->findDot('user.id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" style="text-decoration: none;">';
                $value = $this->resolveValue($context->findDot('user.firstname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' ';
                $value = $this->resolveValue($context->findDot('user.lastname'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></br>';
                // 'userdate' section
                $value = $context->find('userdate');
                $buffer .= $this->sectionD4bf969fa7502a5a202d9ef7b5be17f3($context, $indent, $value);
                $buffer .= '</td>
';
                $buffer .= $indent . '                </tr>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5c7acc8579c065da40b4029f733200aa(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
      <div class="divScroll">
      <div data-role="container" class="h-300">
      <div data-role="content">
      <div class="widget-content tab-content bg-white">
      <div class="box-body table-responsive">
        <table class="table table-hover">
            <tbody>
              <tr>
                <th>{{#str}}discussion, forum{{/str}}</th>
                <th>{{#str}}activity, moodle{{/str}}</th>
                <th>{{#str}}users, moodle{{/str}}</th>
                <th>{{#str}}replies, forum{{/str}}</th>
                <th>{{#str}}lastpost, forum{{/str}}</th>
              </tr>
              {{# recentforums }}
                 <tr class=\'clickable-row\' data-href= \'\'>
                    <td><a href = "{{# content }}{{ config.wwwroot }}/mod/forum/discuss.php?d={{ discussion }}#p{{ id }}{{/ content }}" style="text-decoration: none;">{{ content.subject }}</a></td>
                    <td><a href="{{ config.wwwroot }}/mod/forum/view.php?id={{ cmid }}" style="text-decoration: none;">{{ courseshortname }} / {{ forumname }}</a></td>
                    <td style ="width:20%;">
                    {{# recentuser}}
                        <a href="{{ config.wwwroot }}/user/profile.php?id={{ id }}" style="text-decoration: none;">
                        <img src="{{ profilepicture }}" class="avatar" alt="#">
                        </a>
                    {{/ recentuser}}
                  </td>
                  <td>{{ replies }}</td>
                  <td> <a href="{{ config.wwwroot }}/user/profile.php?id={{ user.id }}" style="text-decoration: none;">{{ user.firstname }} {{ user.lastname }}</a></br>{{#userdate}} {{ timestamp }}, %A, %d %B %Y {{/userdate}}</td>
                </tr>
              {{/ recentforums }}
            </tbody>
        </table>
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
                
                $buffer .= $indent . '      <div class="divScroll">
';
                $buffer .= $indent . '      <div data-role="container" class="h-300">
';
                $buffer .= $indent . '      <div data-role="content">
';
                $buffer .= $indent . '      <div class="widget-content tab-content bg-white">
';
                $buffer .= $indent . '      <div class="box-body table-responsive">
';
                $buffer .= $indent . '        <table class="table table-hover">
';
                $buffer .= $indent . '            <tbody>
';
                $buffer .= $indent . '              <tr>
';
                $buffer .= $indent . '                <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section95a75ee8cc740b2b29528243fa7bec5a($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '                <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section028e961e735b9df9d12ae3ead5122a5e($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '                <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB6ede2badb1bdaf8351eb6fff9f25b62($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '                <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionC513e7f1bd8c373874ab1df75f8a8502($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '                <th>';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section82fa1bb8fbda0b28c313a5dbc2912744($context, $indent, $value);
                $buffer .= '</th>
';
                $buffer .= $indent . '              </tr>
';
                // 'recentforums' section
                $value = $context->find('recentforums');
                $buffer .= $this->section74e777cd27fdd99fa4dc13b19e546e65($context, $indent, $value);
                $buffer .= $indent . '            </tbody>
';
                $buffer .= $indent . '        </table>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE7afea090a62a3082b453179da012d12(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'norecentlyactiveforums, theme_remui';
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
                
                $buffer .= 'norecentlyactiveforums, theme_remui';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
