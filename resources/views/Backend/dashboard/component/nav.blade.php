<div id="url-container" data-url="{{ url('ajax/location/getLocation') }}"></div>
<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button class="navbar-toggle minimalize-styl-2 btn btn-primary" type="button" data-toggle="collapse" data-target="#sidebar">
                <i class="fa fa-bars"></i>
            </button>
           
        </div>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-top-links navbar-right">
                <li>
                <div class="language-container">
                @foreach($languages as $key => $val)
                    <form action="{{ route('language.switch', $val->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="language-item {{ ($val->current == 1) ? 'active' : '' }}" 
                        style="border: none; cursor: pointer; padding: 0;">
                        <img class="image-language" src="{{ $val->image }}" alt="" style="width: 35px !important; height: 25px !important; object-fit: cover !important; border: 1px solid #ddd;">
                        </button>
                    </form>
                @endforeach
                </div>
                </li>
                <li>
                    <form action="{{ route('auth.logout') }}" method="POST" class="navbar-form">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-sign-out"></i> Log out
                        </button>
                    </form>
                </li>
        </div>
    </nav>
</div>
