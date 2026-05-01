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
            $table->boolean('is_active')->default(true);
            $table->date('expiry_date');
            $table->string('academicLevel')->nullable();
            $table->integer('pis_id')->nullable();
            $table->string('affiliation')->nullable();
            $table->float('budget_limit')->nullable();  
            $table->string('managed_Lab_Locations')->nullable();
            $table->string('audit_scope')->nullable();
            $table->string('systemPrivileges')->default('none');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropIfExists('users');  
        });
    }
};