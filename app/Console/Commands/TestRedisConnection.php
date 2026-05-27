<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\UserSessionService;

class TestRedisConnection extends Command
{
    protected $signature = 'redis:test';
    protected $description = 'Testa a conexão com Redis e armazenamento de dados';

    public function handle()
    {
        $this->info('🔍 Testando conexão com Redis...');

        try {
            // Teste 1: Conectar ao Redis
            Cache::store('redis')->put('test_key', 'test_value', 60);
            $value = Cache::store('redis')->get('test_key');
            
            if ($value === 'test_value') {
                $this->line('✅ Conexão com Redis: OK');
            } else {
                $this->error('❌ Valor não encontrado no Redis');
                return 1;
            }

            // Teste 2: Limpar valor de teste
            Cache::store('redis')->forget('test_key');
            
            // Teste 3: Armazenar dados de usuário mock
            $mockUserData = [
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'profession' => 'pedreiro',
                'cpf' => '12345678900',
                'cnpj' => null,
                'google_id' => null,
                'avatar' => null,
            ];
            
            Cache::store('redis')->put('user:session:1', $mockUserData, 86400);
            $this->line('✅ Dados de usuário armazenados no Redis');

            // Teste 4: Recuperar dados
            $retrieved = Cache::store('redis')->get('user:session:1');
            if ($retrieved) {
                $this->line('✅ Dados recuperados do Redis:');
                foreach ($retrieved as $key => $val) {
                    $this->line("  - $key: " . ($val ?? 'null'));
                }
            }

            // Teste 5: Limpar
            Cache::store('redis')->forget('user:session:1');

            $this->info('✅ Todos os testes passaram com sucesso!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao testar Redis: ' . $e->getMessage());
            return 1;
        }
    }
}
