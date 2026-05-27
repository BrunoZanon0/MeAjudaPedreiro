<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Repository\DashboardRepository;

class DashboardController extends Controller
{
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $trabalhos = $this->repository->getUserTrabalhosCollection();
        
        $pedreiros = collect();
        
        if ($user->profession === 'consultor' && $request->has('query')) {
            $pedreiros = $this->repository->searchPedreiros($request->get('query'));
        }
        
        return view('dashboard.dashboard', compact('trabalhos', 'pedreiros'));
    }

    public function showPerfil()
    {
        $data = $this->repository->getUserProfileData();
        
        return view('dashboard.perfil', $data);
    }

    public function editPerfil()
    {
        $user = Auth::user();
        return view('dashboard.editar-perfil', compact('user'));
    }

    public function showTrabalhoFromPerfil($tag, $id)
    {
        $user = $this->repository->getUserByTagOrFail($tag);
        $trabalho = $this->repository->getUserTrabalhoByIdOrFail($user, $id);

        return view('trabalhos.show', compact('trabalho'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();
        
        $validated = $this->repository->validateProfileData($request->all(), $user);
        
        $this->repository->updateProfile($validated, $user);

        return redirect()
            ->route('dashboard.perfil')
            ->with('success', 'Perfil atualizado com sucesso.');
    }

    public function showPerfilWithTag($tag)
    {
        $user = $this->repository->getUserByTagOrFail($tag);
        $data = $this->repository->getUserProfileData($user);
        
        return view('dashboard.perfil', $data);
    }
}