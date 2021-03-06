<?php
namespace App\Controllers\API\v0;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Helpers\Filepond;
class FilepondController extends BaseController
{
    /**
     * @var Filepond
     */
    private $filepond;
    public function __construct(Filepond $filepond)
    {
        $this->filepond = $filepond;
    }
    /**
     * Uploads the file to the temporary directory
     * and returns an encrypted path to the file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $file = $request->file('file')[0];
        $filePath = tempnam(sys_get_temp_dir(), "laravel-filepond");
        // $filePathParts = pathinfo($filePath);
        // if(!$file->move($filePathParts['dirname'], $filePathParts['basename'])) {
        //     return Response::make('Could not save file', 500);
        // }
        return Response::make($this->filepond->getServerIdFromPath($filePath), 200);
    }
    /**
     * Takes the given encrypted filepath and deletes
     * it if it hasn't been tampered with
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request) {
        $filePath = $this->filepond->getPathFromServerId($request->getContent());
        if(unlink($filePath)) {
            return Response::make('', 200);
        } else {
            return Response::make('', 500);
        }
    }
}