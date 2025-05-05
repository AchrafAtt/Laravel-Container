<?php

namespace App\Services\Shared;

use App\Enums\RuleContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class CrudService
{
    /**
     * The Eloquent model class name.
     */
    protected string $model;

    /**
     * Get all records.
     */
    public function index()
    {
        return $this->model::query();
    }

    /**
     * Get a specific record.
     */
    public function show($id)
    {
        return $this->model::query()->where('id', $id);
    }

    /**
     * Create a new record.
     */
    public function store(array $data): object
    {
        $this->validate($data, RuleContext::CREATE);
        
        if (auth()->check()) {
            $data['created_by'] = auth()->id();
        }
        
        return $this->model::create($data);
    }

    /**
     * Update an existing record.
     */
    public function update($id, array $data): object
    {
        $model = $this->model::findOrFail($id);
        $this->validate($data, RuleContext::UPDATE, $model);
        
        if (auth()->check()) {
            $data['updated_by'] = auth()->id();
        }
        
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Delete a record.
     */
    public function destroy($id): object
    {
        $model = $this->model::findOrFail($id);
        $model->delete();
        return $model;
    }

    /**
     * Validate data based on context.
     */
    protected function validate(array $data, RuleContext $context, ?Model $model = null): void
    {
        $modelClass = $this->model;
        if (method_exists($modelClass, 'rules')) {
            $model = $model ?? new $modelClass;
            $rules = $model->rules($context);
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }
}