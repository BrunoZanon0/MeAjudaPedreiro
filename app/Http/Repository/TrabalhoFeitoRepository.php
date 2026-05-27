<?php

namespace App\Http\Repository;

use App\Models\TrabalhoFeito;
use App\Models\User;
use App\Services\UserSessionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrabalhoFeitoRepository
{
    /**
     * @var TrabalhoFeito
     */
    protected $model;

    public function __construct(TrabalhoFeito $model)
    {
        $this->model = $model;
    }

    /**
     * Verificar se usuário tem CPF/CNPJ preenchido
     */
    public function hasCpfOrCnpj(?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        return UserSessionService::hasCpfOrCnpj($userId);
    }

    /**
     * Verificar se usuário é pedreiro
     */
    public function isPedreiro(?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        return UserSessionService::getProfession($userId) === 'pedreiro';
    }

    /**
     * Verificar se usuário tem permissão para gerenciar trabalho
     */
    public function hasPermission(TrabalhoFeito $trabalho, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        return $trabalho->user_id === $userId;
    }

    /**
     * Validar dados do trabalho
     */
    public function validateData(array $data): array
    {
        return validator($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'avaliacao' => 'nullable|integer|min:1|max:5',
            'preco' => 'nullable|numeric|min:0',
            'tempo_gasto' => 'nullable|string|max:255',
            'localizacao' => 'nullable|string|max:255',
        ])->validate();
    }

    /**
     * Upload e processamento de imagens
     */
    public function uploadImages(?array $images): array
    {
        if (!$images) {
            return [];
        }

        $uploadedImages = [];
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store('trabalhos', 'public');
                $uploadedImages[] = $path;
            }
        }
        return $uploadedImages;
    }

    /**
     * Criar novo trabalho
     */
    public function create(array $data, ?int $userId = null): TrabalhoFeito
    {
        $userId = $userId ?? Auth::id();
        
        return $this->model->create([
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'images' => $data['images'] ?? [],
            'avaliacao' => $data['avaliacao'] ?? null,
            'preco' => $data['preco'] ?? null,
            'tempo_gasto' => $data['tempo_gasto'] ?? null,
            'localizacao' => $data['localizacao'] ?? null,
        ]);
    }

    /**
     * Buscar trabalho por ID
     */
    public function findById(int $id): ?TrabalhoFeito
    {
        return $this->model->with('user')->find($id);
    }

    /**
     * Buscar trabalho ou falhar
     */
    public function findOrFail(int $id): TrabalhoFeito
    {
        return $this->model->with('user')->findOrFail($id);
    }

    /**
     * Buscar todos trabalhos do usuário
     */
    public function getUserTrabalhos(?int $userId = null, int $perPage = 10)
    {
        $userId = $userId ?? Auth::id();
        return $this->model->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Buscar trabalhos de um pedreiro específico
     */
    public function getTrabalhosByPedreiro(int $pedreiroId, int $perPage = 10)
    {
        return $this->model->where('user_id', $pedreiroId)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Buscar todos trabalhos (para feed)
     */
    public function getAllTrabalhos(int $perPage = 12)
    {
        return $this->model->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Buscar trabalhos com filtros
     */
    public function getFilteredTrabalhos(array $filters = [], int $perPage = 12)
    {
        $query = $this->model->with('user');

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('description', 'LIKE', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['min_avaliacao'])) {
            $query->where('avaliacao', '>=', $filters['min_avaliacao']);
        }

        if (!empty($filters['max_preco'])) {
            $query->where('preco', '<=', $filters['max_preco']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Atualizar trabalho
     */
    public function update(int $id, array $data): ?TrabalhoFeito
    {
        $trabalho = $this->findById($id);
        
        if (!$trabalho) {
            return null;
        }

        $trabalho->update($data);
        return $trabalho;
    }

    /**
     * Deletar trabalho
     */
    public function delete(int $id): bool
    {
        $trabalho = $this->findById($id);
        
        if (!$trabalho) {
            return false;
        }

        // Deletar imagens associadas
        if ($trabalho->images) {
            foreach ($trabalho->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        return $trabalho->delete();
    }

    /**
     * Deletar imagem específica do trabalho
     */
    public function deleteImage(int $id, string $imagePath): bool
    {
        $trabalho = $this->findById($id);
        
        if (!$trabalho || !$trabalho->images) {
            return false;
        }

        $images = $trabalho->images;
        if (($key = array_search($imagePath, $images)) !== false) {
            unset($images[$key]);
            Storage::disk('public')->delete($imagePath);
            $trabalho->images = array_values($images);
            $trabalho->save();
            return true;
        }

        return false;
    }

    /**
     * Contar trabalhos do usuário
     */
    public function countUserTrabalhos(?int $userId = null): int
    {
        $userId = $userId ?? Auth::id();
        return $this->model->where('user_id', $userId)->count();
    }

    /**
     * Buscar trabalhos por período
     */
    public function getTrabalhosByPeriod(string $startDate, string $endDate, ?int $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        return $this->model->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }
}