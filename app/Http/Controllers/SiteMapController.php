<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Admin;
use Customer;
use Medicine;
use Invoice;
use PaymentGateway;
use Prescription;
use Setting;
use User;

class SiteMap
{
    const START_TAG = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    const END_TAG = '</urlset>';

    // to build the XML content
    private $content;

    public function add(Url $siteMapUrl)
    {
        $this->content .= $siteMapUrl->build();
    }

    public function build()
    {
        return self::START_TAG . $this->content . self::END_TAG;
    }
}

class Url
{
    private $url;
    private $lastUpdate;
    private $frequency;
    private $priority;

    public static function create($url)
    {
        $newNode = new self();
        $newNode->url = url($url);
        return $newNode;
    }

    public function lastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public function frequency($frequency)
    {
        $this->frequency = $frequency;
        return $this;
    }

    public function priority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function build()
    {
        // $url = 'https://programacionymas.com/';
        // $lastUpdate = '2019-07-31T01:06:39+00:00';
        // $frequency = 'monthly';
        // $priority = '1.00';
        return "<url>" .
            "<loc>$this->url</loc>" .
            "<lastmod>$this->lastUpdate</lastmod>" .
            "<changefreq>$this->frequency</changefreq>" .
            "<priority>$this->priority</priority>" .
        "</url>";
    }
}

class SitemapController extends Controller
{

  public function index()
  {
      $this->siteMap = new SiteMap();


      $this->addUniqueRoutes();
      $this->addMedicines();


      return response($this->siteMap->build(), 200)
        ->header('Content-Type', 'text/xml');
  }

  private function addUniqueRoutes()
  {
      $startOfMonth = Carbon::now()->startOfMonth()->format('c');

    $this->siteMap->add(
        Url::create('/')
            ->lastUpdate($startOfMonth)
            ->frequency('monthly')
            ->priority('1.00')
    );

    $this->siteMap->add(
        Url::create('/medicines')
            ->lastUpdate($startOfMonth)
            ->frequency('monthly')
            ->priority('0.8')
    );

    $this->siteMap->add(
        Url::create('/prescriptions')
            ->lastUpdate($startOfMonth)
            ->frequency('yearly')
            ->priority('0.8')
    );

    $this->siteMap->add(
        Url::create('/admin')
            ->lastUpdate($startOfMonth)
            ->frequency('monthly')
            ->priority('0.7')
    );

  }

  private function addProfilePages()
  {
      // ...
  }

  private function addMedicines()
  {
      $medicines = Medicine::published()->whereNotNull('slug')->get([
        'slug', 'updated_at'
      ]);

      foreach ($medicines as $medicine) {
          $this->siteMap->add(
              Url::create("/medicine/$medicine->slug")
                  ->lastUpdate($medicine->updated_at->startOfMonth()->format('c'))
                  ->frequency('monthly')
                  ->priority('0.9')
          );
      }
  }

  private function addCategories()
  {
      // ...
  }

  private function addTags()
  {
      // ...
  }

  private function addProjects()
  {
      // ...
  }

  private function addDynamicPages()
  {
      // ...
  }

}