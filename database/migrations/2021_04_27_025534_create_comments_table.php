<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('message');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reply_id')->nullable()->constrained('comments', 'id')->onDelete('cascade');
            $table->foreignId('addon_id')->constrained()->onDelete('cascade');

            $table->boolean('pinned')->default(false);

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
        Schema::dropIfExists('comments');
    }
}
