<nav class="navbar-default navbar-static-side" id="sidebar" role="navigation">
    @php
    $segment=request()->segment(1);
    @endphp
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" src="{{asset('giaodien/img/profile_small.jpg')}}" />
                    </span>
                    <span class="clear">
                        <span class="block m-t-xs">
                            <strong class="font-bold">Minh Sơn</strong>
                        </span>
                    </span>
                </div>
            </li>

            @foreach(__('sidebar.module') as $key => $val)
            <li class="{{ (in_array($segment, $val['name'])) ? 'active' : '' }}">
                <a href="">
                    <i class="{{ $val['icon'] }}"></i>
                    <span class="nav-label">{{ $val['title'] }}</span>
                    <span class="fa arrow"></span>
                </a>
                @if(isset($val['subModule']))
                <ul class="nav nav-second-level">
                    @foreach($val['subModule'] as $module)
                    <li><a href="{{ $module['route'] }}">{{ $module['title'] }}</a></li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</nav>