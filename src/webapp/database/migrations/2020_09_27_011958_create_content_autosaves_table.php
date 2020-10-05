<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentAutosavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_autosaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id');
            $table->bigInteger('content_id')->nullable();
            $table->string('title', 255)->comment('タイトル');
            $table->string('description', 255)->nullable()->comment('概要（ディスクリプション）');
            $table->mediumText('text')->nullable()->comment('本文');
            $table->mediumText('note')->nullable()->comment('備考');
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();

            $table->unique(['client_id', 'content_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_autosaves');
    }
}
