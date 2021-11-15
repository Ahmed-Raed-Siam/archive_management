<div class="container flex-grow-1 light-style container-p-y">
    <div class="container-m-nx container-m-ny bg-lightest mb-3">
        <ol class="breadcrumb text-big container-p-x py-3 m-0">
            <li class="breadcrumb-item">
                <a>Dashboard</a>
            </li>
        </ol>

        <hr class="m-0" />

        <div class="file-manager-actions container-p-x py-2">
            <div>
                <form action="{{ route('file.store') }}" method="POST" id="fomrFiles" enctype="multipart/form-data">
                    @csrf
                    <label class="btn btn-primary mr-2" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02"><i class="far ion-md-cloud-upload"></i>&nbsp; Upload</label>
                    <input type="file" class="custom-file-input" id="inputGroupFile02" name="files[]" hidden multiple onchange="document.getElementById('fomrFiles').submit();">
                </form>

                <button type="button" class="btn btn-secondary icon-btn mr-2" disabled=""><i class="ion ion-md-cloud-download"></i></button>
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-default md-btn-flat dropdown-toggle px-2" data-toggle="dropdown"><i class="ion ion-ios-settings"></i></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item">Move</a>
                        <a class="dropdown-item">Copy</a>
                        <a class="dropdown-item">Remove</a>
{{--                        <form class="dropdown-item"--}}
{{--                              action="{{ route('file.destroy',$id) }}"--}}
{{--                              method="POST">--}}
{{--                            @csrf--}}
{{--                            @method('DELETE')--}}
{{--                            {{ csrf_field() }}--}}
{{--                            <button class="dropdown-item p-0" type="submit" data-toggle="tooltip"--}}
{{--                                    data-placement="top"--}}
{{--                                    title="Delete folder {{ $name }}">--}}
{{--                                Remove--}}
{{--                            </button>--}}
{{--                        </form>--}}
                    </div>
                </div>
            </div>
            <div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-default icon-btn md-btn-flat active"> <input type="radio" name="file-manager-view" value="file-manager-col-view" checked="" /> <span class="ion ion-md-apps"></span> </label>
                    <label class="btn btn-default icon-btn md-btn-flat"> <input type="radio" name="file-manager-view" value="file-manager-row-view" /> <span class="ion ion-md-menu"></span> </label>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    new Folder
                </button>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('file.store')}}" method="POST" id="newFolder">
                                    @csrf
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Name folder:</label>
                                        <input type="text" class="form-control" name="folder" id="Name folder">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" onclick="document.getElementById('newFolder').submit();" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" />
    </div>
