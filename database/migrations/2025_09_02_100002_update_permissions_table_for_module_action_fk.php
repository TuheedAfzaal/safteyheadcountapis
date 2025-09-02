<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->after('id');
            $table->unsignedBigInteger('action_id')->after('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->dropColumn(['module', 'action']);
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module');
            $table->string('action');
            $table->dropForeign(['module_id']);
            $table->dropForeign(['action_id']);
            $table->dropColumn(['module_id', 'action_id']);
        });
    }
};
