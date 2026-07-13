<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cpr_records', function (Blueprint $table) {
            // Composite index for folder + filename lookups (most common query)
            $table->index(['folder_path', 'filename'], 'cpr_records_folder_filename_idx');
            
            // Index for filtering by status
            $table->index('status', 'cpr_records_status_idx');
            
            // Index for sorting by filename number
            $table->index('filename_sort', 'cpr_records_filename_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('cpr_records', function (Blueprint $table) {
            $table->dropIndex('cpr_records_folder_filename_idx');
            $table->dropIndex('cpr_records_status_idx');
            $table->dropIndex('cpr_records_filename_sort_idx');
        });
    }
};