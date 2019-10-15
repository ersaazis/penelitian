<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\Core\Browser\Browser;
use Serps\SearchEngine\Google\NaturalResultType;
use App\Keyword;
use App\DataKeyword;

class GetGoogle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:google {cari}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GetGoogle';

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
    	$cari = $this->argument('cari');
		$userAgent = "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36";
		$browserLanguage = "id-ID";
		$browser = new Browser(new CurlClient(), $userAgent, $browserLanguage);
		$googleClient = new GoogleClient($browser);
		$googleUrl = new GoogleUrl('google.co.id');
		$googleUrl->setSearchTerm($cari);
		$googleUrl->setResultsPerPage(100);
		$response = $googleClient->query($googleUrl);
		$results = $response->getNaturalResults();
		$keyword = Keyword::create(['keyword' => $cari]);
		foreach($results as $result){
	        if($result->is(NaturalResultType::CLASSICAL)){
				$DataKeywordeyword = DataKeyword::create([
					'keyword_id'=>$keyword->id,
					'title'=>$result->title,
					'url'=>$result->url
				]);
                Artisan::queue('get:article', ['id' => $DataKeywordeyword->id])
                    ->onConnection('database')->onQueue('article');
	        }
		}
    }
}
