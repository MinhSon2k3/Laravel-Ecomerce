
<nav class="navbar-default navbar-static-side" role="navigation">
    @php
        $segment=request()->segment(1);
    @endphp
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="{{asset('giaodien/img/profile_small.jpg')}}" />
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Minh Sơn</strong>
                             </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="profile.html">Profile</a></li>
                            <li><a href="contacts.html">Contacts</a></li>
                            <li><a href="mailbox.html">Mailbox</a></li>
                            <li class="divider"></li>
                         
                        </ul>
                    </div>
                  
                </li>

                @php
                $modules = config('apps.module.module');
                @endphp

                @if(!empty($modules) && is_array($modules))
                    @foreach($modules as $module)
                        <li class="{{($segment==$module['name']) ? 'active' : ''}}">
                            <a href="#">
                                {!! $module['icon'] ?? '' !!}
                                <span class="nav-label">{{ $module['title'] ?? '' }}</span>
                                <span class="fa arrow"></span>
                            </a>
                            @if(!empty($module['subModule']))
                                <ul class="nav nav-second-level">
                                    @foreach($module['subModule'] as $subModule)
                                        <li>
                                            <a href="{{ url($subModule['route']) }}">
                                                {{ $subModule['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>

        </div>
</nav>