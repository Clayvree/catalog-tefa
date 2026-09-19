<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    /**
     * Menyimpan hasil ekstraksi AI dari file chat WA menjadi Project dan Tasks secara utuh.
     */
    public function saveAiExtractionResult(string $tefaUnitId, string $creatorId, array $validatedAiData): ?Project
    {
        try {
            return DB::transaction(function () use ($tefaUnitId, $creatorId, $validatedAiData) {
                $project = Project::create([
                    'tefa_unit_id'         => $tefaUnitId,
                    'title'                => $validatedAiData['project_title'],
                    'client_name'          => $validatedAiData['client_name'],
                    'client_contact'       => $validatedAiData['client_contact'] ?? null,
                    'description'          => $validatedAiData['project_summary'],
                    'final_price'          => $validatedAiData['agreed_price'] ?? null,
                    'ai_extraction_data'   => $validatedAiData['raw_json'] ?? null,
                    'source_chat_file_url' => $validatedAiData['chat_file_url'] ?? null,
                    'created_by'           => $creatorId,
                    'status'               => \App\Enums\ProjectStatus::Active,
                ]);

                foreach ($validatedAiData['tasks'] as $aiTask) {
                    Task::create([
                        'project_id'              => $project->id,
                        'tefa_unit_id'            => $tefaUnitId,
                        'title'                   => $aiTask['title'],
                        'description'             => $aiTask['instructions'],
                        'skill_id'                => $aiTask['recommended_skill_id'] ?? null,
                        'ai_recommendation_notes' => $aiTask['reasoning'] ?? null,
                        'priority'                => \App\Enums\TaskPriority::Medium,
                        'status'                  => \App\Enums\TaskStatus::Todo,
                    ]);
                }

                return $project->load('tasks');
            });
        } catch (\Exception $e) {
            Log::error('Failed to save AI extraction result: ' . $e->getMessage());
            throw $e;
        }
    }
}
