<x-filament-panels::page>
    <div class="space-y-4">
        <div class="flex gap-4 overflow-x-auto pb-4">
            @foreach($this->getStages() as $stage)
                <div class="flex-shrink-0 w-80">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-lg" style="color: {{ $stage->color }}">
                                    {{ $stage->name }}
                                </h3>
                                <span class="text-sm text-gray-500">
                                    {{ $this->getLeadsByStage($stage->id)->count() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4 space-y-3 min-h-[200px]" 
                             data-stage-id="{{ $stage->id }}"
                             ondrop="drop(event)" 
                             ondragover="allowDrop(event)">
                            @foreach($this->getLeadsByStage($stage->id) as $lead)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg cursor-move hover:shadow-md transition"
                                     draggable="true"
                                     ondragstart="drag(event)"
                                     data-lead-id="{{ $lead->id }}">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $lead->contact_name }}
                                        </div>
                                        
                                        @if($lead->company)
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $lead->company }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500">
                                                {{ $lead->user->name }}
                                            </span>
                                            
                                            @if($lead->total_opportunity_value > 0)
                                                <span class="font-semibold text-green-600">
                                                    R$ {{ number_format($lead->total_opportunity_value, 2, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($lead->hasOverdueActivities())
                                            <div class="flex items-center gap-1 text-xs text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Atividades atrasadas
                                            </div>
                                        @endif
                                        
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-600">
                                            <a href="{{ route('filament.admin.resources.leads.edit', $lead) }}" 
                                               class="text-xs text-primary-600 hover:text-primary-700">
                                                Ver detalhes →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("leadId", ev.target.getAttribute('data-lead-id'));
        }

        function drop(ev) {
            ev.preventDefault();
            const leadId = ev.dataTransfer.getData("leadId");
            const newStageId = ev.currentTarget.getAttribute('data-stage-id');
            
            @this.call('updateLeadStage', leadId, newStageId);
        }

        Livewire.on('lead-updated', () => {
            location.reload();
        });
    </script>
</x-filament-panels::page>
