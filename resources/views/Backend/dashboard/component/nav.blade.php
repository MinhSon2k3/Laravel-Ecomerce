<div id="url-container" data-url="{{ url('ajax/location/getLocation') }}"></div>
<div id="sidebar-overlay"></div>

<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button class="navbar-toggle minimalize-styl-2 btn btn-primary" type="button">
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
                                <img class="image-language" src="{{ $val->image }}" alt=""
                                    style="width: 35px !important; height: 25px !important; object-fit: cover !important; border: 1px solid #ddd;">
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
            </ul>
        </div>
    </nav>
</div>

<style>
#sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 2000;
    display: none;
}

@media (max-width: 768px) {
    .navbar-static-side {
        display: none;
        background: white;
        transition: all 0.3s ease-in-out;
        transform: translateX(-100%);
    }

    .navbar-static-side.show-sidebar {
        display: block !important;
        position: fixed;
        z-index: 2001;
        width: 220px;
        height: 100vh;
        left: 0;
        top: 0;
        transform: translateX(0);
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    #sidebar-overlay.show {
        display: block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleButton = document.querySelector('.navbar-toggle');
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebar-overlay');

    if (toggleButton && sidebar && overlay) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Toggle clicked');
            sidebar.classList.toggle('show-sidebar');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show-sidebar');
            overlay.classList.remove('show');
        });
    } else {
        console.error('Toggle button or sidebar not found');
    }

    // Thêm xử lý khi resize window
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show-sidebar');
            overlay.classList.remove('show');
        }
    });
});
</script>