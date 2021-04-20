<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 24)->unique();
            $table->text('description')->nullable();
            $table->text('source');
            $table->json('images')->nullable();
            $table->json('thumbnail')->nullable();
            $table->json('contributors')->nullable();
            $table->text('download');
            $table->date('release');
            $table->string('type', 12);
            $table->bigInteger('view_count')->default(0);
            $table->bigInteger('download_count')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('addons');
    }
}
