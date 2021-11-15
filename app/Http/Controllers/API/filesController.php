<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilesResource;
use App\Models\files;
use \Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class filesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
//        $files = files::orderByRaw("type <> 'folder'")->where('user_id', Auth::guard('sanctum')->id())->whereNull('parent_id')->paginate();
//        $files = $user->load('folders_files')
//            ->where('user_id', '=', $user->id)
//            ->whereNull('parent_id')->get();

//        $files = files::orderByRaw("type <> 'folder'")->where('user_id', $user->id)->where('parent_id', null)->get();
//        $files = $user->folders_files()->whereNull('parent_id')->get();
        $files = $user->folders_files()->orderByRaw("type <> 'folder'")->whereNull('parent_id')->get();

//        $files = $user->folders_files()->get('');
        return response()->json([
            'files' => $files,
//            $user,
//            'user_id'=>$user->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $user_name = str_replace(' ', '_', $user->name);
        $uploaded_files = array();

        $parent_folder_id = (int)$request->post('parent_folder');
        $num = (int)($parent_folder_id);
        $find = files::find($num);
//        return response()->json([
//            'parent_folder_id_input' => $parent_folder_id,
//            'num' => $num,
//            'find' => $find,
//            'parent_folder_path' => $find->file_path,
//        ]);


        if ($find === null) :
            if ($request->post('folder')) :
                $folder = new files();
                $folder->name = $request->post('folder');
                $folder->parent_id = null;
                $folder->user_id = Auth::id();
                $folder->type = 'folder';
                $folder->file_path = $user_name . '/' . $folder->name;
                $folder->save();
                return Response::json([
                        'message' => 'You Create new Folder --' . $folder->name,
                        'url' => route('dashboard.file.show', $folder->id),
                    ]
                    , 200);
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
                    $uploaded_files[] = [
                        'file' => $newFile,
                    ];
                endforeach;
            endif;
            return Response::json([
                    'message' => 'You Add new Files to the home page --',
                    'uploaded_files' => $uploaded_files,
                ]
                , 200);
        else:
            if ($request->post('folder')) :
                $parent_folder_path = $find->file_path;
                $folder = new files();
                $folder->name = $request->post('folder');
                $folder->parent_id = $num;
                $folder->user_id = Auth::id();
                $folder->type = 'folder';
                $folder->file_path = $parent_folder_path . '/' . $folder->name;
                $folder->save();
                return Response::json([
                        'message' => 'You Create new Folder --' . $folder->name . ' inside ' . $find->name . ' Folder',
                        'folder' => $folder,
                        'url' => route('dashboard.file.show', $folder->id),
                    ]
                    , 200);
            endif;

            if ($request->hasFile('files')) :
                foreach ($request->file('files') as $file):

                    $name = $file->getClientOriginalName();
                    $parent_folder = $find->parent();

                    if ($parent_folder->count() > 0) :
                        $parent_folder = $find->parent();
                        $parent_folder_name = $parent_folder->first()->name;
                        $parent_folder_path = $parent_folder->first()->file_path;

                        // To get full path
//                        $parent_folder_path . '/' . $find->name
                        $path = $file->store($find->file_path, [
                            'disk' => 'uploads',
                        ]);

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

                    $newFile->save();

                    $uploaded_files[] = [
                        'file' => $newFile,
                    ];

                endforeach;
                return Response::json([
                    'message' => 'You Add new Files to ' . $find->name . ' Folder --',
                    'parent_folder' => $find,
                    'uploaded_files' => $uploaded_files,
                ], 200);
            endif;
//            return redirect()->back();
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return FilesResource
     */
    public function show(int $id): FilesResource
    {
        $user = Auth::guard('sanctum');
        $file = files::where('id', $id)->first();
        return new FilesResource($file);
//        return response()->json([$user->user(), $file, $file->children], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function destroy($id)
    {
        $file = files::findOrFail($id);
        $children = $file->children();
        $deleted_files = [];
        $message = '';

        if (count($children->get()) > 0):
            foreach ($children->get() as $child):
                $file_path = $child->file_path;
                //Check file/folder path is exists in the disk
                if (Storage::disk('uploads')->exists($file_path)):
                    if ($child->type === 'folder'):
                        Storage::disk('uploads')->deleteDirectory($file_path);
                        Storage::disk('uploads')->delete($file_path);

                        // Delete Child folder content
                        $child->children()->forceDelete();
                    else:
                        Storage::disk('uploads')->delete($file_path);
                    endif;
                    $message = 'You Remove ' . $child->name . ' inside ' . $file->name . ' Folder -- From the Database';
                    $deleted_files[] = [
                        'file' => $child,
                    ];

                endif;

            endforeach;

            //Delete from database
            $children->forceDelete();

        endif;

        // Delete single folder or file
        $file_path = $file->file_path;
        // When We use Storage::disk('uploads') we must pass the path without Disk folder like: ahmed_raed_siam/Root not like the C:\xampp\htdocs\sFiles-main\public\/uploads/ahmed_raed_siam/Root/wmVquOlp5lsmwRwNgGyVdbjeDK18nL5bDlJcKCGw.png
        if (Storage::disk('uploads')->exists($file->file_path)):
            if ($file->type === 'folder'):
                Storage::disk('uploads')->deleteDirectory($file_path);
                $message = 'You Remove ' . $file->name . ' Folder from the database';

            else:
                Storage::disk('uploads')->delete($file_path);
                $message = 'You Remove ' . $file->name . ' File from the database';
            endif;
        endif;
        $file->forceDelete();
        if (count($deleted_files) > 0):
            return Response::json([
                'message' => $message,
                'parent_folder' => $file,
                'deleted_files' => $deleted_files,
            ], 200);
        endif;
        return Response::json([
            'message' => $message,
            'parent_folder' => $file,
        ], 200);
    }
}
