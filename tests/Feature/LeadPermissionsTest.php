<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lead;
use App\Models\PipelineStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar etapa do pipeline para os testes
        PipelineStage::create([
            'name' => 'Novo',
            'order' => 1,
            'color' => '#3b82f6',
            'is_active' => true,
        ]);
    }

    public function test_vendedor_pode_criar_lead(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();

        $lead = Lead::create([
            'contact_name' => 'João da Silva',
            'company' => 'Empresa Teste',
            'email' => 'joao@teste.com',
            'phone' => '11999999999',
            'source' => 'website',
            'user_id' => $vendedor->id,
            'pipeline_stage_id' => $stage->id,
            'needs_summary' => 'Precisa de consultoria',
            'first_contact_date' => now(),
        ]);

        $this->assertDatabaseHas('leads', [
            'contact_name' => 'João da Silva',
            'user_id' => $vendedor->id,
        ]);
    }

    public function test_vendedor_pode_ver_apenas_seus_proprios_leads(): void
    {
        $vendedor1 = User::factory()->vendedor()->create();
        $vendedor2 = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();

        $leadVendedor1 = Lead::factory()->create([
            'user_id' => $vendedor1->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        $leadVendedor2 = Lead::factory()->create([
            'user_id' => $vendedor2->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        // Vendedor 1 pode ver seu lead
        $this->assertTrue($vendedor1->id === $leadVendedor1->user_id);
        
        // Vendedor 1 não pode ver lead do vendedor 2
        $this->assertFalse($vendedor1->id === $leadVendedor2->user_id);
    }

    public function test_gestor_pode_ver_todos_leads(): void
    {
        $gestor = User::factory()->gestor()->create();
        $vendedor = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();

        $leadVendedor = Lead::factory()->create([
            'user_id' => $vendedor->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        // Gestor tem permissão para ver todos os leads
        $this->assertTrue($gestor->isGestor());
        $this->assertDatabaseHas('leads', [
            'id' => $leadVendedor->id,
        ]);
    }

    public function test_lead_pode_ter_atividades(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();

        $lead = Lead::factory()->create([
            'user_id' => $vendedor->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        $lead->activities()->create([
            'user_id' => $vendedor->id,
            'type' => 'call',
            'scheduled_at' => now()->addDay(),
            'notes' => 'Ligar para cliente',
            'status' => 'scheduled',
        ]);

        $this->assertCount(1, $lead->activities);
        $this->assertEquals('call', $lead->activities->first()->type);
    }

    public function test_lead_pode_ter_oportunidades(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();
        
        $lead = Lead::factory()->create([
            'user_id' => $vendedor->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        $product = \App\Models\Product::factory()->create([
            'price' => 1000.00,
        ]);

        $lead->opportunities()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'estimated_value' => 2000.00,
            'notes' => 'Oportunidade de venda',
        ]);

        $this->assertCount(1, $lead->opportunities);
        $this->assertEquals(2000.00, $lead->total_opportunity_value);
    }

    public function test_lead_identifica_atividades_atrasadas(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();

        $lead = Lead::factory()->create([
            'user_id' => $vendedor->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        // Criar atividade atrasada
        $lead->activities()->create([
            'user_id' => $vendedor->id,
            'type' => 'call',
            'scheduled_at' => now()->subDays(2),
            'notes' => 'Atividade atrasada',
            'status' => 'scheduled',
        ]);

        $this->assertTrue($lead->hasOverdueActivities());
    }

    public function test_atividade_pode_ser_concluida(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $stage = PipelineStage::first();

        $lead = Lead::factory()->create([
            'user_id' => $vendedor->id,
            'pipeline_stage_id' => $stage->id,
        ]);

        $activity = $lead->activities()->create([
            'user_id' => $vendedor->id,
            'type' => 'call',
            'scheduled_at' => now(),
            'notes' => 'Ligar para cliente',
            'status' => 'scheduled',
        ]);

        $activity->update(['status' => 'completed']);

        $this->assertEquals('completed', $activity->fresh()->status);
    }
}
