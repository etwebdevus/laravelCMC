<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id');
            $table->bigInteger('locale');
            $table->string('title');
            $table->string('link');
            $table->text('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
            $table->text('notes')->nullable();
            $table->bigInteger('cloned')->nullable();
            $table->bigInteger("connect_same")->comment("Connect Same Page With Defferent Language");
            $table->bigInteger('status')->comment('0 Inactive,1 Active');
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
        Schema::dropIfExists('pages_translations');
    }
}
