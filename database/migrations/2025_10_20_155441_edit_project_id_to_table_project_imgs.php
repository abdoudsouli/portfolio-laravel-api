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
        Schema::table('project_imgs', function (Blueprint $table) {
          // Drop old foreign key first if it exists
          $table->dropForeign(['project_id']);
          // Rename the column if you want (optional)
          //$table->renameColumn('id_user', 'post_id');
          $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_imgs', function (Blueprint $table) {
            //
        });
    }
};
