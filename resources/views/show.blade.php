@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <x-layout-new />
        <div class="file-manager-container file-manager-col-view">
            <div class="file-manager-row-header">
                <div class="file-item-name pb-2">Filename</div>
                <div class="file-item-changed pb-2">Changed</div>
            </div>
            <div class="file-item">
                <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"
                     onclick="location.href='{{ route('file.show', $parent_id->parent_id ?? '') }}'"></div>
                <a class="file-item-name" href="{{ route('file.show', $parent_id->parent_id ?? '') }}">
                    ..
                </a>
            </div>
            <x-files :files="$files" />
        </div>
    </div>

</div>
</div>
@endsection
