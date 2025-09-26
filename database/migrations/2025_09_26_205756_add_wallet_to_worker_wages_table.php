<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worker_wages', function (Blueprint $table) {
            $table->decimal('wallet', 10, 2)->default(0)->after('wage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worker_wages', function (Blueprint $table) {
            $table->dropColumn('wallet');
        });
    }
};
