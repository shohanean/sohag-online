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
        Schema::create('transections', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('campaign_id');
            $table->decimal('old_amount', 10, 2)->nullable();
            $table->decimal('current_amount', 10, 2)->nullable();
            $table->decimal('spent_amount', 10, 2)->nullable();
            $table->decimal('dollar_rate', 10, 2)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_type');
            $table->unsignedBigInteger('added_id');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('transections');
    }
};
