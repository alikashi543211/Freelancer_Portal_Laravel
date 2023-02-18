<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_leads', function (Blueprint $table) {
            $table->bigInteger('bid_id');
            $table->integer('status')->unsigned()->default(PENDING);
            $table->string('status_slug')->default('pending');
            $table->integer('watch')->unsigned()->default(ACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_leads', function (Blueprint $table) {
            $table->dropColumn('bid_id');
            $table->dropColumn('status');
            $table->dropColumn('status_slug');
            $table->dropColumn('watch');
        });
    }
}
