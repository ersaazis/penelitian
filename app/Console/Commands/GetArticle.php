<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use App\DataKeyword;

class GetArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:article {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GetArticle';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $DK=DataKeyword::find($id);
        if($DK){
            $data = file_get_contents($DK->url);
            $data = strip_tags($data,"<style>");
            $substring = substr($data,strpos($data,"<style"),strpos($data,"</style>")+2);
            $data = str_replace($substring,"",$data);
            $data = str_replace(array("\t","\r","\n"),"",$data);
            $data = trim($data);
            $fileName='scrap/'.md5(time().rand(1111,9999)).'.html';
            Storage::disk('local')->put($fileName, $data);
            $DK->file='app/'.$fileName;
            $DK->save();
        }
    }
}
