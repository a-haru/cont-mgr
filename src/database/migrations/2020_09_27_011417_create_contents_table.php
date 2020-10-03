<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->comment('クライアントID');
            $table->string('title', 255)->comment('タイトル');
            $table->string('description', 255)->nullable()->comment('概要（ディスクリプション）');
            $table->mediumText('text')->nullable()->comment('本文');
            $table->mediumText('note')->nullable()->comment('備考');
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->softDeletes();

            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
