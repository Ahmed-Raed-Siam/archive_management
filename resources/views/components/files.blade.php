@foreach ($files as $file )
    @if ($file->type === 'folder')
        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'far fa-folder'"
                            :created="$file->created_at" />
    @endif

    @if($file->file_type === 'application/pdf')
        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'far fa-file-pdf'"
                            :created="$file->created_at"/>

    @elseif ($file->file_type === 'application/zip')
        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'far fa-file-archive'"
                            :created="$file->created_at"/>

    @elseif ($file->file_type === 'text/plain')
        @if ( substr($file->name, strrpos($file->name, '.')+1) === 'js')
            <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fab fa-js'"
                                :created="$file->created_at"/>

        @elseif (substr( $file->name, strrpos($file->name, '.')+1) === 'sql')
            <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fas fa-database'"
                                :created="$file->created_at"/>

        @elseif (substr($file->name, strrpos($file->name, '.')+1) === 'doc')
            <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fas fa-file-word'"
                                :created="$file->created_at"/>

        @elseif (substr($file->name, strrpos($file->name, '.')+1) === 'css')
            <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fab fa-css3-alt'"
                                :created="$file->created_at"/>

        @elseif (substr($file->name, strrpos($file->name, '.')+1) === 'txt')
            <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fas fa-file-alt'"
                                :created="$file->created_at"/>

        @endif
    @endif

    @if ($file->file_type === 'image/svg')
        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fas fa-code-branch'"
                            :created="$file->created_at"/>
    @endif

    @if($file->file_type === 'text/html')
        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fab fa-html5'"
                            :created="$file->created_at"/>
    @endif

    @if ($file->file_type === 'image/png')
        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fab fa-html5'"
                            :created="$file->created_at"
                                :file="$file->file_path"/>
            @endif


            @if ($file->file_type === 'image/jpeg')
                <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'fab fa-html5'"
                                    :created="$file->created_at"
                                        :file="$file->file_path"/>
                    @endif


                    @if ($file->file_type === 'application/json')
                        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'far fa-hand-lizard'"
                                            :created="$file->created_at"/>
                    @endif

                    @if ($file->file_type === 'audio/mpeg')
                        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'far fa-file-audio'"
                                            :created="$file->created_at"/>
                    @endif

                    @if ($file->file_type === 'video/mp4')
                        <x-layout-file-item :id="$file->id" :name="$file->name" :icon="'far fa-file-video'"
                                            :created="$file->created_at"/>
    @endif

@endforeach
