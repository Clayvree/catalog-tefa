<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Menyimpan hasil ekstraksi AI dari file chat WA menjadi Project dan Tasks secara utuh.
     */
    public function saveAiExtractionResult(string $tefaUnitId, string $creatorId, array $validatedAiData): ?Project
    {
        try {
            $projectData = [
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
            ];

            $tasksData = [];
            foreach ($validatedAiData['tasks'] as $aiTask) {
                $tasksData[] = [
                    'title'                   => $aiTask['title'],
                    'description'             => $aiTask['instructions'],
                    'skill_id'                => $aiTask['recommended_skill_id'] ?? null,
                    'ai_recommendation_notes' => $aiTask['reasoning'] ?? null,
                    'priority'                => \App\Enums\TaskPriority::Medium,
                ];
            }

            return $this->projectRepository->createWithTasks($projectData, $tasksData);
        } catch (\Exception $e) {
            Log::error('Failed to save AI extraction result: ' . $e->getMessage());
            throw $e;
        }
    }
}
