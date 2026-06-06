<?php

namespace App\Services;

use App\Models\LessonContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class LessonContentService
{
    public function getAll(): Collection
    {
        return LessonContent::query()
            ->with('course')
            ->orderBy('course_id')
            ->orderBy('module_number')
            ->orderBy('position')
            ->get();
    }

    public function getByType(string $type): Collection
    {
        return LessonContent::query()
            ->with('course')
            ->where('content_type', $type)
            ->orderBy('course_id')
            ->orderBy('module_number')
            ->orderBy('position')
            ->get();
    }

    public function store(array $data): LessonContent
    {
        $data = $this->prepareData($data);

        try {
            return DB::transaction(fn () => LessonContent::create($data));
        } catch (Throwable $exception) {
            Log::error('Failed to create lesson content.', ['data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('មិនអាចបង្កើតមាតិកាមេរៀនបានទេ។ សូមព្យាយាមម្តងទៀត។', 0, $exception);
        }
    }

    public function update(int $id, array $data): LessonContent
    {
        $data = $this->prepareData($data, $id);

        try {
            return DB::transaction(function () use ($id, $data) {
                $lessonContent = $this->findById($id);
                $lessonContent->update($data);

                return $lessonContent;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update lesson content.', ['id' => $id, 'data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('មិនអាចកែប្រែមាតិកាមេរៀនបានទេ។ សូមព្យាយាមម្តងទៀត។', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(fn () => (bool) $this->findById($id)->delete());
        } catch (Throwable $exception) {
            Log::error('Failed to delete lesson content.', ['id' => $id, 'error' => $exception->getMessage()]);

            throw new RuntimeException('មិនអាចលុបមាតិកាមេរៀនបានទេ។ សូមព្យាយាមម្តងទៀត។', 0, $exception);
        }
    }

    public function findById(int $id): LessonContent
    {
        return LessonContent::query()->with('course')->findOrFail($id);
    }

    protected function prepareData(array $data, ?int $ignoreId = null): array
    {
        $data['completion_required'] = (bool) ($data['completion_required'] ?? false);
        $data['allow_comments'] = (bool) ($data['allow_comments'] ?? false);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['title'], $ignoreId);
        $data['metadata'] = filled($data['metadata'] ?? null) ? json_decode($data['metadata'], true) : null;

        return $data;
    }

    protected function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value) ?: 'lesson-content';
        $slug = $baseSlug;
        $counter = 2;

        while (
            LessonContent::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
