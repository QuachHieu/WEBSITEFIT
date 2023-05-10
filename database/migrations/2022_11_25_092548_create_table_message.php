<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_message', function (Blueprint $table) {
            $table->id();
            $table->string('user_key')->comment('id_id');
            $table->string('media')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_receive_id')->nullable()->constrained('admins');
            $table->dateTime('date_at')->nullable()->comment('Đọc lần đầu lúc');
            $table->string('status',20)->default('active');
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
        Schema::dropIfExists('tb_message');
    }
}
