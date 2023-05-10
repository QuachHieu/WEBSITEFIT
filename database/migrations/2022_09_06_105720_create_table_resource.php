<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_resource', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->nullable()->constrained('tb_cms_taxonomys');
            $table->string('title');
            $table->string('brief');
            $table->longText('content');
            $table->json('json_params')->nullable();
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->integer('view')->default(0);
            $table->integer('iorder')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
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
        Schema::dropIfExists('tb_resource');
    }
}
