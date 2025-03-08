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
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_by')->nullable()->after('liked_by');
            $table->unsignedBigInteger('reply_by')->nullable()->after('comment_by');
            
            $table->foreign('comment_by')->references('user_id')->on('user_registration')->onDelete('set null');
            $table->foreign('reply_by')->references('user_id')->on('user_registration')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['comment_by']);
            $table->dropForeign(['reply_by']);
            $table->dropColumn(['comment_by', 'reply_by']);
        });
    }
};
