<?php

namespace App\Http\Controllers\Api\Author;

use App\Models\Author;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;
use App\Traits\FilesTrait;

class AuthorController extends Controller
{
    use FilesTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $authors = [];

        try {
            $columns = ['id','name','last_name','email'];
            $perPage = intval($request->input('per_page'));
            $query   = $request->input('query');

            $authors = Author::ofSearch($query)
            ->orderBy('name')
            ->paginate($perPage,$columns);

            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
        }

        return response()->json([
            'authors' => $authors,
            'error'   => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AuthorRequest $request)
    {
        \DB::beginTransaction();

        try {
            $dataAutor = $request->validated();

            $author  = Author::create($dataAutor);
            $urlFile = $this->putFile($request,'authors');
            $author->file()->create(['url' => $urlFile]);

            \DB::commit();

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Autor creado correctamente';
        } catch (\Throwable $th) {
            \DB::rollback();

            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo crear el autor';
        }

        return response()->json([
            'message' => $this->apiMessage,
            'error'   => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $author = null;

        try {
            $author = Author::with('file')->findOrFail($id);

            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError   = $th->getMessage();
            $this->apiCode    = 404;
        }

        return response()->json([
            'author' => $author,
            'error'  => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AuthorRequest $request, $id)
    {
        try {
            $dataAuhtor = $request->validated();

            $author = Author::findOrFail($id);
            $author->update($dataAuhtor);

            if ($request->hasFile('file')) {
                $author->file()->delete();
                $urlFile = $this->putFile($request,'authors');
                $author->file()->create(['url' => $urlFile]);
            }

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Autor actualizado correctamente';
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo actualizar el autor';
        }

        return response()->json([
            'message' => $this->apiMessage,
            'error'   => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Autor eliminado correctamente';
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo eliminar el autor';
        }

        return response()->json([
            'message' => $this->apiMessage,
            'error'   => $this->apiError
        ], $this->apiCode);
    }

    public function list()
    {
        $authors = [];

        try {
            $columns = ['id','name','last_name','email'];

            $authors = Author::orderBy('name')->get($columns);

            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
        }

        return response()->json([
            'authors' => $authors,
            'error'   => $this->apiError
        ], $this->apiCode);
    }
}