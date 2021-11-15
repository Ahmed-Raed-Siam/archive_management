<div class="file-item">
    <div class="file-item-select-bg bg-primary"></div>
    <label class="file-item-checkbox custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input"/>
        <span class="custom-control-label"></span>
    </label>
    @if ( isset($file) )
        <div class="file-item-img" style="background-image: url({{ asset('uploads' . '/' . $file) }});"
             onclick="location.href='{{ asset('uploads' . '/' . $file) }}'"></div>

        <a class="file-item-name" href="{{ asset('uploads' . '/' . $file) }}">
            {{ $name }}
        </a>
    @else
        <div class="file-item-icon {{$icon}} text-secondary"
             onclick="location.href='{{ route('file.show', $id) }}'"></div>

        <a class="file-item-name" href="{{ route('file.show', $id) }}">
            {{ $name }}
        </a>
    @endif

    <div class="file-item-changed">{{$created}}</div>
    <div class="file-item-actions btn-group">
        <button type="button"
                class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle"
                data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item">Rename</a>
            <a class="dropdown-item">Move</a>
            <a class="dropdown-item">Copy</a>
            {{--            <a class="dropdown-item">Remove</a>--}}
            <form class="dropdown-item"
                  action="{{ route('file.destroy',$id) }}"
                  method="POST">
                @csrf
                @method('DELETE')
                {{ csrf_field() }}
                <button class="dropdown-item p-0" type="submit" data-toggle="tooltip"
                        data-placement="top"
                        title="Delete folder {{ $name }}">
                    Remove
                </button>
            </form>
        </div>
    </div>
</div>
