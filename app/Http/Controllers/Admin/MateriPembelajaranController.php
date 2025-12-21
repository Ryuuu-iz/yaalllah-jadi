<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Services\MaterialService;
use Illuminate\Http\Request;

class MateriPembelajaranController extends Controller
{
    protected $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    /**
     * Display a listing of all materials
     */
    public function index(Request $request)
    {
        $materi = $this->materialService->getMaterialsWithFilters($request);

        return view('admin.materials.index', compact('materi'));
    }

    /**
     * Show the form for creating a new material
     */
    public function create()
    {
        return view('admin.materials.create');
    }

    /**
     * Store a newly created material
     */
    public function store(StoreMaterialRequest $request)
    {
        $this->materialService->createMaterial($request->validated());

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Material successfully created');
    }

    /**
     * Display the specified material
     */
    public function show($id)
    {
        $material = $this->materialService->getMaterialById($id);

        return view('admin.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material
     */
    public function edit($id)
    {
        $material = $this->materialService->getMaterialById($id);

        return view('admin.materials.edit', compact('material'));
    }

    /**
     * Update the specified material
     */
    public function update(UpdateMaterialRequest $request, $id)
    {
        $material = $this->materialService->getMaterialById($id);
        $this->materialService->updateMaterial($material, $request->validated());

        return redirect()->route('admin.materials.show', $material->id_materi)
                        ->with('success', 'Material successfully updated');
    }

    /**
     * Remove the specified material
     */
    public function destroy($id)
    {
        $material = $this->materialService->getMaterialById($id);
        $this->materialService->deleteMaterial($material);

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Material successfully deleted');
    }
}