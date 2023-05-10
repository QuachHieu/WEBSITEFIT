<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedTableCmsDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cms_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('tb_department');
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_receive_id')->nullable()->constrained('admins');
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->dateTime('date_at')->nullable()->comment('Đọc lần đầu lúc');
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
        Schema::dropIfExists('tb_cms_document');
    }
}
