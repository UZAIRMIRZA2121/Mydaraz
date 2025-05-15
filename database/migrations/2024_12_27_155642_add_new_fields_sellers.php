<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('google_id')->nullable();
            $table->string('radio_check')->nullable();
            $table->string('business_day')->nullable();
            $table->string('establishment')->nullable();
            $table->string('upload_certifice')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_address')->nullable();
            $table->string('brief_here')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('city')->nullable();
            $table->string('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        //
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn('radio_check');
            $table->dropColumn('business_day');
            $table->dropColumn('establishment');
            $table->dropColumn('upload_certifice');
            $table->dropColumn('shop_name');
            $table->dropColumn('shop_address');
            $table->dropColumn('brief_here');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('city');
            $table->dropColumn('category');
        });
    }
};
