<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_pro')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('immatricul')->nullable();

             // add columns 
             $table->string('personal_email')->nullable()->after('email_pro');
             $table->string('profile_picture')->nullable()->after('password');
             $table->enum('family_situation', ['single', 'married', 'divorced'])->nullable()->after('profile_picture');
             $table->string('position')->nullable()->after('family_situation');
             $table->string('address')->nullable()->after('position');
             $table->string('country', 100)->nullable()->after('address');
             $table->string('id_number', 50)->nullable()->after('country');
             $table->string('ssn', 50)->nullable()->after('id_number');
             $table->string('bank_account', 50)->nullable()->after('ssn');
             $table->string('birth_place')->nullable()->after('bank_account');
             $table->unsignedInteger('children_count')->nullable()->after('birth_place');
             $table->enum('contract_type', ['FullTime', 'PartTime', 'Contractor'])->nullable()->after('children_count');
             $table->decimal('leave_balance', 8, 2)->nullable()->default(0)->after('contract_type');
             $table->date('hire_date')->nullable()->after('leave_balance');
             


            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users') ;
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email_pro')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
