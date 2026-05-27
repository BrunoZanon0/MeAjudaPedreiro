<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\TrabalhoFeitoRepository;
use App\Services\UserSessionService;

class TrabalhoFeitoController extends Controller
{
    protected $repository;

    public function __construct(TrabalhoFeitoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Verificar permissões básicas
     */
    private function checkPermissions()
    {
        if (!$this->repository->hasCpfOrCnpj()) {
            return redirect()->route('cpf.cnpj.form')
                ->with('info', 'Preencha seu CPF ou CNPJ antes de continuar.');
        }

        if (!$this->repository->isPedreiro()) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Apenas pedreiros podem cadastrar trabalhos.');
        }

        return null;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($redirect = $this->checkPermissions()) {
            return $redirect;
        }

        return view('trabalhos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkPermissions()) {
            return $redirect;
        }

        // Validar dados
        $validated = $this->repository->validateData($request->all());
        
        // Upload das imagens
        $images = $this->repository->uploadImages($request->file('images'));
        $validated['images'] = $images;

        // Criar trabalho
        $this->repository->create($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Trabalho cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$this->repository->hasCpfOrCnpj()) {
            return redirect()->route('cpf.cnpj.form')
                ->with('info', 'Preencha seu CPF ou CNPJ antes de continuar.');
        }

        $trabalho = $this->repository->findOrFail($id);
        
        // Verificar permissão
        if (!$this->repository->hasPermission($trabalho)) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Você não tem permissão para ver este trabalho.');
        }
        
        return view('trabalhos.show', compact('trabalho'));
    }

    /**
     * Display pedreiro trabalhos
     */
    public function showPedreiro($id)
    {
        if (!$this->repository->hasCpfOrCnpj()) {
            return redirect()->route('cpf.cnpj.form')
                ->with('info', 'Preencha seu CPF ou CNPJ antes de continuar.');
        }

        $pedreiro = User::where('profession', 'pedreiro')->findOrFail($id);
        $trabalhos = $this->repository->getTrabalhosByPedreiro($id);
        
        return view('pedreiros.show', compact('pedreiro', 'trabalhos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ($redirect = $this->checkPermissions()) {
            return $redirect;
        }

        $trabalho = $this->repository->findOrFail($id);
        
        if (!$this->repository->hasPermission($trabalho)) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Você não tem permissão para editar este trabalho.');
        }

        return view('trabalhos.edit', compact('trabalho'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($redirect = $this->checkPermissions()) {
            return $redirect;
        }

        $trabalho = $this->repository->findOrFail($id);
        
        if (!$this->repository->hasPermission($trabalho)) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Você não tem permissão para editar este trabalho.');
        }

        // Validar dados
        $validated = $this->repository->validateData($request->all());
        
        // Upload de novas imagens se houver
        if ($request->hasFile('images')) {
            $newImages = $this->repository->uploadImages($request->file('images'));
            $validated['images'] = array_merge($trabalho->images ?? [], $newImages);
        }

        // Atualizar trabalho
        $this->repository->update($id, $validated);

        return redirect()->route('trabalhos.show', $id)
            ->with('success', 'Trabalho atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($redirect = $this->checkPermissions()) {
            return $redirect;
        }

        $trabalho = $this->repository->findOrFail($id);
        
        if (!$this->repository->hasPermission($trabalho)) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Você não tem permissão para deletar este trabalho.');
        }

        $this->repository->delete($id);

        return redirect()->route('dashboard.index')
            ->with('success', 'Trabalho deletado com sucesso!');
    }

    /**
     * Deletar imagem específica
     */
    public function deleteImage(string $id, Request $request)
    {
        if ($redirect = $this->checkPermissions()) {
            return $redirect;
        }

        $request->validate([
            'image_path' => 'required|string'
        ]);

        $deleted = $this->repository->deleteImage($id, $request->image_path);

        if (!$deleted) {
            return response()->json(['error' => 'Imagem não encontrada'], 404);
        }

        return response()->json(['success' => 'Imagem deletada com sucesso']);
    }
}