@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['index']['title']])
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{route('system.store')}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content aminated fadeInRight">
        @foreach($systemConfig as $key => $val)
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        <h4>{{$val['label']}}</h4>
                    </div>
                    <div class="panel-decsription">
                        {{$val['description']}}
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    @if(count($val['value']))
                    <div class="ibox-content">
                        @foreach($val['value'] as $keyVal => $item)
                        @php
                        $name=$key.'_'.$keyVal;
                        @endphp
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="uk-flex uk-flex-space-between">
                                        <span style="margin-right: auto !important"> {{$item['label']}}</span>
                                        <span style="margin-left: auto !important;">
                                            {!!renderSystemLink($item)!!}</span>
                                        {!!renderSystemTitle($item)!!}</span>
                                    </label>
                                    @switch($item['type'])
                                    @case('text')
                                    {!!renderSystemInput($name, $systems)!!}
                                    @break
                                    @case('image')
                                    {!!renderSystemImage($name, $systems)!!}
                                    @break
                                    @case('textarea')
                                    {!!renderSystemTextarea($name, $systems)!!}
                                    @break
                                    @case('select')
                                    {!!renderSystemSelect($item,$name, $systems)!!}
                                    @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">Lưu lại</button>
        </div>

    </div>
</form>