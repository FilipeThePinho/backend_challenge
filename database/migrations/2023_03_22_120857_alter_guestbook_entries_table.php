<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('guestbook_entries', function (Blueprint $table) {
            $table->dropColumn([
                'submitter_email',
                'submitter_display_name',
                'submitter_real_name',
            ]);

            $table->integer('submitter_id')->after('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('guestbook_entries', function (Blueprint $table) {
            $table->string('submitter_email')->nullable();
            $table->string('submitter_display_name')->nullable();
            $table->string('submitter_real_name')->nullable();

            $table->dropColumn(['submitter_id']);
        });

    }
};
