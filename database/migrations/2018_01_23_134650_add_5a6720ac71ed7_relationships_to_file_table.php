<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class Add5a6720ac71ed7RelationshipsToFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function(Blueprint $table) {
            if (!Schema::hasColumn('files', 'folder_id')) {
                $table->integer('folder_id')->unsigned()->nullable();
                $table->foreign('folder_id', '110370_5a6720aac53d3')->references('id')->on('folders')->onDelete('cascade');
                }
                if (!Schema::hasColumn('files', 'created_by_id')) {
                $table->integer('created_by_id')->unsigned()->nullable();
                $table->foreign('created_by_id', '110370_5a6720aad15f8')->references('id')->on('users')->onDelete('cascade');
                }
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function(Blueprint $table) {
            
        });
    }
}
