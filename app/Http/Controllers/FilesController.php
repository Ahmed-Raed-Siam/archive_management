<?php

namespace App\Http\Controllers;

use App\Models\files;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        //
        $files = files::orderByRaw("type <> 'folder'")->where('user_id', Auth::id())->where('parent_id', null)->get();
        return response()->view('home', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $user_name = str_replace(' ', '_', Auth::user()->name);

        // dd($request->all());
        $var = explode('/', $request->server('HTTP_REFERER'));
        $num = (int)end($var);
        $find = files::find($num);
//        dd(
//            $find,
//            $request,
//            $request->file(),
//        );
        if ($find === null) :
            if ($request->post('folder')) :
                $folder = new files();
                $folder->name = $request->post('folder');
                $folder->parent_id = null;
                $folder->user_id = Auth::id();
                $folder->type = 'folder';
                $folder->file_path = $user_name . '/' . $folder->name;
                $folder->save();
            endif;
            if ($request->hasFile('files')) :
                foreach ($request->file('files') as $file) :
                    $name = $file->getClientOriginalName();
                    $path = $file->store($user_name, [
                        'disk' => 'uploads',
                    ]);
                    $newFile = new files();
                    $newFile->name = $name;
                    $newFile->parent_id = null;
                    $newFile->user_id = Auth::id();
                    $newFile->type = 'file';
                    $newFile->file_path = $path;
                    $newFile->file_size = $file->getSize();
                    $newFile->file_type = $file->getMimeType();
                    $newFile->save();
                endforeach;
            endif;
            return redirect()->back();
        else:
            if ($request->post('folder')) :
//                $parent_folder = $find->parent();
                $parent_folder = $find->file_path;
//                dd(
//                    $find,
////                    $request,
//                    $request->file(),
//                    'File Path',
//                    $find->name,
//                    $find->file_path,
//                    $parent_folder,
//                    $parent_folder->,
//                );
                $parent_folder_path = $find->file_path;
                $folder = new files();
                $folder->name = $request->post('folder');
                $folder->parent_id = $num;
                $folder->user_id = Auth::id();
                $folder->type = 'folder';
//                $folder->file_path = '/uploads/' . $user_name . '/' . $folder->name;
//                $folder->file_path = $user_name . '/' . $find->name . '/' . $folder->name;
                $folder->file_path = $parent_folder_path . '/' . $folder->name;
//                dd(
//                    $find,
//                    $request,
//                    $request->file(),
////                    $parent_folder->first(),
//                    $folder->file_path,
//                    'File Path',
//                    $find->name,
//                    $find->file_path,
//                    $parent_folder_path,
//                    $parent_folder_path . '/' . $folder->name,
//                );
                $folder->save();
            endif;

            if ($request->hasFile('files')) :
                foreach ($request->file('files') as $file):
                    $name = $file->getClientOriginalName();
                    $parent_folder = $find->parent();
//                    dd(
//                        $parent_folder,
//                        $parent_folder->first(),
//                        $parent_folder_name,
//                        $parent_folder_path,
//                        $parent_folder_path . '/' . $find->name,
//                        $name,
//                        $find,
//                        $find->name,
//                    );
                    if ($parent_folder->count() > 0) :
                        $parent_folder = $find->parent();
                        $parent_folder_name = $parent_folder->first()->name;
                        $parent_folder_path = $parent_folder->first()->file_path;

//                        dd(
//                            $name,
//                            $parent_folder,
//                            $parent_folder->count(),
//                            $parent_folder->first(),
//                            $parent_folder_name,
//                            $parent_folder_path,
//                            'asdsadsa',
//                            $find->file_path,
//                            $find->name,
//                            $name,
//                            $parent_folder_path . '/' . $find->name,
//                        );
                        // To get full path
//                        $parent_folder_path . '/' . $find->name
                        $path = $file->store($find->file_path, [
                            'disk' => 'uploads',
                        ]);
//                        dd(
//                            $parent_folder,
//                            $parent_folder->count(),
//                            $parent_folder->first(),
//                            $parent_folder_name,
//                            $path,
//                        );
                    else:
                        $path = $file->store($user_name . '/' . $find->name, [
                            'disk' => 'uploads',
                        ]);
                    endif;

                    $newFile = new files();
                    $newFile->name = $name;
                    $newFile->parent_id = $num;
                    $newFile->user_id = Auth::id();
                    $newFile->type = 'file';
                    $newFile->file_path = $path;
                    $newFile->file_size = $file->getSize();
                    $newFile->file_type = $file->getMimeType();
//                    dd(
//                        $find,
//                        $parent_folder->get(),
////                        $parent_folder->name,
//                        $request,
//                        $request->file(),
//                        $newFile->file_path,
//                    );
                    $newFile->save();
                endforeach;
            endif;
            return redirect()->back();
        endif;

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\files $files
     * @return Response
     */
    public function show($id)
    {
        //
        $files = files::where('parent_id', $id)->get();
        $parent_id = files::where('id', $id)->first();
        // dd($parent_id);
        return view('show', compact('files', 'parent_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\files $files
     * @return Response
     */
    public function edit(files $files)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\files $files
     * @return Response
     */
    public function update(Request $request, files $files)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $file = files::findOrFail($id);
        $children = $file->children();
        if (count($children->get()) > 0):
//            dd(
////                $file_path,
//                $children->get(),
//            );
            foreach ($children->get() as $child):
                $file_path = $child->file_path;
//                dd(
//                    'FOR LOOp',
//                    $child,
//                    $file_path,
//                    $child->type,
//                    $children->get(),
//                );
                //Check file/folder path is exists in the disk
                if (Storage::disk('uploads')->exists($file_path)):
                    if ($child->type === 'folder'):
//                        dd(
//                            $child,
//                            $child->type,
//                            $file_path,
//                            $children->get(),
//                            'sadsadsadsadsadas',
//                            Storage::disk('uploads')->allDirectories(),
//                        );
                        Storage::disk('uploads')->deleteDirectory($file_path);
                        Storage::disk('uploads')->delete($file_path);
                        // Delete Child folder content
                        $child->children()->forceDelete();
                    else:
                        Storage::disk('uploads')->delete($file_path);
                        // OR unlink but with full path like C:\xampp\htdocs\sFiles-main\public\/uploads/ahmed_raed_siam/Root/kwvVMucSlA4TGqZKGbQoe7IqS3UXj3Z1RVT36eCI.png
                        //unlink(public_path('/uploads') . '/' . $file_path);
                    endif;
//                    dd(
//                        'After for Loop',
//                        $child,
//                        $child->type === 'folder',
//                        $file_path,
//                        $children->get(),
//                    );
                endif;
//                dd(
//                    'You delete',
//                    $child,
//                    $file_path,
//                );
            endforeach;

            //Delete from database
            $children->forceDelete();
//            dd(
//                'You',
//                $children,
//            );
        endif;

        // Delete single folder or file
        $file_path = $file->file_path;
//        $file_path = public_path('/uploads') . '/' . $file->file_path;
//        dd(
//            $file,
//            $children->get(),
//            $file_path,
//            Storage::disk('local')->exists('C:\xampp\htdocs\sFiles-main\public\/uploads/ahmed_raed_siam/Root/wmVquOlp5lsmwRwNgGyVdbjeDK18nL5bDlJcKCGw.png'),
//            Storage::disk('local')->exists($file_path),
//        );
        // When We use Storage::disk('uploads') we must pass the path without Disk folder like: ahmed_raed_siam/Root not like the C:\xampp\htdocs\sFiles-main\public\/uploads/ahmed_raed_siam/Root/wmVquOlp5lsmwRwNgGyVdbjeDK18nL5bDlJcKCGw.png
        if (Storage::disk('uploads')->exists($file->file_path)):
//            dd(
//                'yes',
//                $file,
//                $children->get(),
//                $file_path,
//                Storage::disk('uploads')->allFiles(),
//                Storage::exists('ahmed_raed_siam/Root/wmVquOlp5lsmwRwNgGyVdbjeDK18nL5bDlJcKCGw.png'),
//                '-------------------------------------------------',
//                Storage::disk('uploads')->exists('ahmed_raed_siam/Root/wmVquOlp5lsmwRwNgGyVdbjeDK18nL5bDlJcKCGw.png'),
//                Storage::disk('uploads')->exists($file_path),
//            );
            if ($file->type === 'folder'):
                Storage::disk('uploads')->deleteDirectory($file_path);
//                dd(
//                    $file,
//                    $file->type,
//                    Storage::disk('uploads')->deleteDirectory($file_path),
//                    Storage::disk('uploads')->allDirectories(),
//                );
            else:
                Storage::disk('uploads')->delete($file_path);
                // OR unlink but with full path like C:\xampp\htdocs\sFiles-main\public\/uploads/ahmed_raed_siam/Root/kwvVMucSlA4TGqZKGbQoe7IqS3UXj3Z1RVT36eCI.png
//                unlink(public_path('/uploads') . '/' . $file_path);
            endif;
        endif;
        $file->forceDelete();
        return redirect()->back();
//        return redirect()->route('file.index');
    }
}
