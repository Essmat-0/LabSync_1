<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('status', 50);
            $table->decimal('hourly_rate', 10, 2)->default(0.00);
            $table->integer('required_clearance')->default(0);
            $table->timestamps();
        });

        Schema::create('equipment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->enum('status', ['Pending', 'Active', 'Completed', 'Cancelled'])->default('Pending');
            $table->string('approval_status', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->date('expiry_date');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('equipment_sessions')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('normalized_amount', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pi_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->decimal('cost', 10, 2);
            $table->text('description'); // Changed to text for longer logs
            $table->timestamps();
        });

        Schema::create('publication_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->string('doi', 100);
            $table->timestamps();
        });

        Schema::create('roi_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->decimal('roi_score', 10, 2);
            $table->string('recommendation', 50);
            $table->timestamps();
        });

        Schema::create('utilization_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->decimal('usage_percentage', 5, 2);
            $table->timestamps();
        });

        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->timestamp('created_at')->useCurrent();
        });

        DB::statement("DROP VIEW IF EXISTS session_summary"); // Good practice to drop first
        DB::statement("
    CREATE VIEW session_summary AS
    SELECT 
        u.name as user_name, 
        e.name AS equipment_name, 
        s.start_time, 
        s.end_time
    FROM equipment_sessions s
    JOIN users u ON s.user_id = u.id
    JOIN equipment e ON s.equipment_id = e.id
");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS session_summary");
        Schema::dropIfExists('audit_trails');
        Schema::dropIfExists('utilization_cache');
        Schema::dropIfExists('roi_reports');
        Schema::dropIfExists('publication_links');
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('grants');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('roles');
    }
};