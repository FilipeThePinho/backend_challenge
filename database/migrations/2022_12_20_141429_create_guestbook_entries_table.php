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
    public function up()
    {
        Schema::create('guestbook_entries', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('title');
            $table->longText('content');
            $table->string('submitter_email');
            $table->string('submitter_display_name');
            $table->string('submitter_real_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guestbook_entries');
    }
};
