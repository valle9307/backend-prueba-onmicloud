<?php

namespace App\Http\Controllers\Api\Editorial;

use App\Models\Editorial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EditorialRequest;

class EditorialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $editorials = [];
        
        try {
            $columns = ['id','name','address','phone_number'];
            $perPage = intval($request->input('per_page'));
            $query   = $request->input('query');

            $editorials = Editorial::ofSearch($query)
            ->withCount('books')
            ->orderBy('name')
            ->paginate($perPage,$columns);
            
            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
        }

        return response()->json([
            'editorials' => $editorials,
            'error'      => $this->apiError
        ], $this->apiCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditorialRequest $request)
    {
        try {
            $dataEditorial = $request->validated();

            Editorial::create($dataEditorial);

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Editorial creada correctamente';
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo crear la editorial';
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
        $editorial = null;

        try {
            $editorial = Editorial::findOrFail($id);

            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError   = $th->getMessage();
            $this->apiCode    = 404;
        }

        return response()->json([
            'editorial'  => $editorial,
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
    public function update(EditorialRequest $request, $id)
    {
        try {
            $dataEditorial = $request->validated();

            $editorial = Editorial::findOrFail($id);
            $editorial->update($dataEditorial);

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Editorial actualizada correctamente';
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo actualizar la editorial';
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
            $editorial = Editorial::findOrFail($id);
            $editorial->delete();

            $this->apiError   = null;
            $this->apiCode    = 200;
            $this->apiMessage = 'Editorial eliminada correctamente';
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
            $this->apiMessage = 'No se pudo eliminar la editorial';
        }

        return response()->json([
            'message' => $this->apiMessage,
            'error'   => $this->apiError
        ], $this->apiCode);
    }

    public function list()
    {
        $editorials = [];
        
        try {
            $columns = ['id','name','address','phone_number'];

            $editorials = Editorial::orderBy('name')->get($columns);
            
            $this->apiError = null;
            $this->apiCode  = 200;
        } catch (\Throwable $th) {
            $this->apiError = $th->getMessage();
            $this->apiCode  = 500;
        }

        return response()->json([
            'editorials' => $editorials,
            'error'      => $this->apiError
        ], $this->apiCode);
    }
}