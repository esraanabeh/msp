<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_links', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->boolean('valid')->default(1);
            $table->string('status')->nullable();
            $table->foreignId('client_id')->constrained('clients','id');
            $table->foreignId('client_quote_id')->constrained('client_quotes','id');
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
        Schema::dropIfExists('quotation_links');
    }
}
