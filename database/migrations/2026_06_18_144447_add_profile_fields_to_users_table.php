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
        Schema::table('users', function (Blueprint $table) {
            // Profile completion tracking - only add if doesn't exist
            if (!Schema::hasColumn('users', 'profile_completed')) {
                $table->boolean('profile_completed')->default(false);
            }
            
            // Personal Information
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            if (!Schema::hasColumn('users', 'national_id')) {
                $table->string('national_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable();
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('users', 'county_of_birth')) {
                $table->string('county_of_birth')->nullable();
            }
            if (!Schema::hasColumn('users', 'county_of_residence')) {
                $table->string('county_of_residence')->nullable();
            }
            if (!Schema::hasColumn('users', 'physical_address')) {
                $table->text('physical_address')->nullable();
            }
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable();
            }
            
            // Profile Image
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
            
            // Additional Fields for Attachees
            if (!Schema::hasColumn('users', 'institution')) {
                $table->string('institution')->nullable();
            }
            if (!Schema::hasColumn('users', 'course')) {
                $table->string('course')->nullable();
            }
            if (!Schema::hasColumn('users', 'attachment_start_date')) {
                $table->date('attachment_start_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'attachment_end_date')) {
                $table->date('attachment_end_date')->nullable();
            }
            
            // Additional Fields for Staff
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable();
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_completed',
                'phone_number',
                'national_id',
                'age',
                'gender',
                'date_of_birth',
                'county_of_birth',
                'county_of_residence',
                'physical_address',
                'emergency_contact',
                'profile_photo_path',
                'institution',
                'course',
                'attachment_start_date',
                'attachment_end_date',
                'department',
                'position'
            ]);
        });
    }
};
