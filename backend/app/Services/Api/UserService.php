<?php

namespace App\Services\Api;

use App\Services\Shared\CrudService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService extends CrudService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->model = User::class;
    }

    /**
     * Override the store method to hash passwords.
     *
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        // Hash password if provided and not empty
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        // Return the user model directly from parent::store
        return parent::store($data);

    }

    /**
     * Override the update method to hash passwords.
     *
     * @param mixed $id
     * @param array $data
     * @return User
     * @throws ModelNotFoundException
     */
    public function update($id, array $data): User
    {
        // Hash password if provided and not empty
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove empty password from update data
            unset($data['password']);
        }
        
       
      // Call parent update method (which will handle validation and updating)
      $model = parent::update($id, $data);
        
      return $model;
    }
}