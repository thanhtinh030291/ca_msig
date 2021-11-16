<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <h1 class="main-title float-left">{{ $title }}</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/home') }}">{{ __('message.home') }}</a></li>
                @if (!empty($parent_url))
                    <li class="breadcrumb-item"><a href="{{ $parent_url }}">{{ $parent_name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $page_name }}</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
