<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadersColumnAndFootersColumnToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('primary_color')->default('#ed3833')->after('logo_color');
            $table->string('secondary_color')->default('#1F1E1E')->after('primary_color');

            $table->string('header_topbar_bg_color')->default('#2A3143')->after('secondary_color');
            $table->string('header_topbar_text_color')->default('#ffffff')->after('header_topbar_bg_color');

            $table->string('header_bg_color')->default('#000000')->after('header_topbar_text_color');
            $table->string('header_text_color')->default('#ffffff')->after('header_bg_color');

            $table->string('header_bottom_bg_color')->default('#ffffff')->after('header_text_color');
            $table->string('header_bottom_text_color')->default('#000000')->after('header_bottom_bg_color');

            $table->string('body_bg_color')->default('#F2F4F8')->after('header_bottom_text_color');
            $table->string('category_bg_color')->default('#ffffff')->after('body_bg_color');

            $table->string('footer_bg_color')->default('#000000')->after('category_bg_color');
            $table->string('footer_text_color')->default('#f2f2f2')->after('footer_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('primary_color');
            $table->dropColumn('secondary_color');

            $table->dropColumn('header_topbar_bg_color');
            $table->dropColumn('header_topbar_text_color');

            $table->dropColumn('header_bg_color');
            $table->dropColumn('header_text_color');

            $table->dropColumn('header_bottom_bg_color');
            $table->dropColumn('header_bottom_text_color');

            $table->dropColumn('body_bg_color');
            $table->dropColumn('category_bg_color');

            $table->dropColumn('footer_bg_color');
            $table->dropColumn('footer_text_color');
        });
    }
}
