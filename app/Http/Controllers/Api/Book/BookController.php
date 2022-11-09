<?php

namespace App\Http\Controllers\Api\Book;

use App\Models\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Traits\FilesTrait;

class BookController extends Controller
{
    use FilesTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $books = [];
        
        try {
            $columns      = ['id','editorial_id','title','publish_at','price'];
            $perPage      = intval($request->input('per_page'));
            $query        = $request->input('query');
            $nombre       = $request->input('nombre');
            $editorial_id = $request->input('editorial_id');

            $books = Book::ofSearch($query)
            ->when($editorial_id, function ($query, $editorial_id) {
                $query->where('editorial_id', $editorial_id);
            })
            ->when($nombre, function ($query, $nombre) {
                $query->where('title','like','%'.$nombre.'%');
            })
            ->with([
                'authors.file',
                'editorial:id,name',
                'file'
            ])
            ->withCount('authors')
            ->orderBy('title')
            ->paginate($perPage,$columns);
            
            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
        }

        return response()->json([
            'books' => $books,
            'error' => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookRequest $request)
    {
        \DB::beginTransaction();
        
        try {
            $dataBook = $request->validated();
            $authors  = $request->input('authors');

            $book = Book::create($dataBook);
            $book->authors()->attach($authors);

            $urlFile = $this->putFile($request,'books');
            $book->file()->create(['url' => $urlFile]);
            
            \DB::commit();

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Libro creado correctamente';
        } catch (\Throwable $th) {
            \DB::rollBack();

            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo crear el libro';
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
        $book = null;

        try {
            $book = Book::with('authors:id,name,last_name')->findOrFail($id);

            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError   = $th->getMessage();
            $this->apiCode    = 404;
        }

        return response()->json([
            'book'  => $book,
            'error' => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BookRequest $request, $id)
    {
        \DB::beginTransaction();
        
        try {
            $dataBook = $request->validated();
            $authors  = $request->input('authors');

            $book = Book::findOrFail($id);
            $book->update($dataBook);
            $book->authors()->sync($authors);

            if ($request->hasFile('file')) {
                $book->file()->delete();
                $urlFile = $this->putFile($request,'books');
                $book->file()->create(['url' => $urlFile]);
            }

            \DB::commit();

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Libro actualizado correctamente';
        } catch (\Throwable $th) {
            \DB::rollBack();

            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo actualizar el libro';
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
        \DB::beginTransaction();

        try {
            $book = Book::findOrFail($id);
            $book->authors()->detach();
            $book->delete();

            \DB::commit();

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Libro eliminado correctamente';
        } catch (\Throwable $th) {
            \DB::rollBack();

            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo eliminar el libro';
        }

        return response()->json([
            'message' => $this->apiMessage,
            'error'   => $this->apiError
        ], $this->apiCode);
    }
}