<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Api\Paypal\Reset\Tracking;
use App\Api\Magento1x\Soap\Payment\TransactionSoap;
use App\Api\Oceanpayment\Tracking as OcTracking;
use App\Entity\Sales\Order\Item;

use App\Api\Magento1x\Soap\Sales\OrderShipmentSoap;

use App\Traits\ConfigTrait;

use App\Api\Geosearch\AddressSearch;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';

    protected $parameter;
    protected $doctrine;

    public function __construct(ParameterBagInterface $parameter, ManagerRegistry $doctrine)
    {      
        parent::__construct();

        $this->parameter = $parameter;
        $this->doctrine = $doctrine;

        ConfigTrait::loadConfigs($doctrine->getManager());
    }

    protected function configure()
    {
        $this
            ->setDescription('Test command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $client = new AddressSearch();
        $r = $client->search([
            'county' => 'US',
            'postalcode' => '80210',
            'state' => 'Colorado',
            'city' => 'Denver',
            'street' => '1317 E Iowa Ave',
        ]);
        print_r($r);
        exit;

        $apiParams = ConfigTrait::configMagentoApi();

        $client = new OrderShipmentSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? '');
        $r = $client->callSalesOrderShipmentSendInfo('100000046');
        var_dump($r);
        exit;

        $merchant_id = '140188853';

        $client = new \Google_Client();

$client->addScope('https://www.googleapis.com/auth/content');      
$client->setAuthConfig('f:/content-api-key.json');

$service = new \Google_Service_ShoppingContent($client);

// try {
//     $product = $service->products->get($merchant_id, 'online:en:US:111111111133');
//     print_r($product);
//     printf("%s %s\n", $product->getId(), $product->getTitle());      
// } catch (\Exception $e) {
//     echo $e->getMessage();
//     echo $e->getCode();
// }
// exit;



$product = new \Google_Service_ShoppingContent_Product();
$product->setKind('content#product');
$product->setOfferId('11111111113');
$product->setTitle('Swetelove Test');
$product->setDescription('Swetelove Test Product');
$product->setLink('https://www.swetelove.com/test.html');
$product->setImageLink('https://www.swetelove.com/media/catalog/product/cache/1/thumbnail/600x/9df78eab33525d08d6e5fb8d27136e95/images/catalog/product/placeholder/thumbnail.jpg');
$product->setContentLanguage('en');
$product->setTargetCountry('US');
$product->setChannel('online');
$product->setAgeGroup('adult');
$product->setAvailability('in stock');
$product->setAvailabilityDate('2019-01-25T13:00:00-08:00');
$product->setBrand('Swetelove');
$product->setColor('black');
$product->setCondition('new');
$product->setGender('male');
$product->setGoogleProductCategory('1604');
$product->setGtin('608802531656');
$product->setItemGroupId('google_tee');
$product->setMpn('608802531656');

$price = new \Google_Service_ShoppingContent_Price();
$price->setCurrency('USD');
$price->setValue(10);
$product->setPrice($price);

$product->setSizes(['Large']);
$results = $service->products->insert($merchant_id, $product);


print_r($results);  
exit;
 
$products = $service->products->listProducts($merchant_id);
echo count($products);
$parameters = array();
while (!empty($products->getResources())) {
  foreach ($products->getResources() as $product) {
    printf("%s %s\n", $product->getId(), $product->getTitle());
  }

  if (empty($products->getNextPageToken())) {
    break;
  }
  $parameters['pageToken'] = $products->nextPageToken;
  $products = $service->products->listProducts($merchant_id, $parameters);
  //print_r($products);
}
        exit;

        // $repository = $this->doctrine->getRepository(Item::class);
        // $items = $repository->findBySku('EY016');
        
        // $filepath = $this->parameter->get('kernel.project_dir') . '/var/item-sku.csv';

        // $fp = fopen($filepath,"w");
        // $data = array(
        //     'sku',
        //     'size',
        // );
        // fputcsv($fp, $data);        
        // foreach ($items as $item) {
        //     $option = $item->getProductOptionSize();

        //     $size = $option['print_value'] ?? '';
        //     $sku = $item->getSku();

        //     $data = compact('sku', 'size');
        //     fputcsv($fp, $data);            
        // }
        // fclose($fp);
        // exit;

        $params = $this->parameter->get('App\Api\Oceanpayment\Tracking');

        $client = new OcTracking($params);
        $r = $client->add([
            'order_number' => '100011878',
            'tracking_number' => 'YT1922621266022914',
            'tracking_site' => 'http://www.yuntrack.com',
        ]);
        var_dump($r);
        exit;

        $client = new TransactionSoap('http://www.swetelove-dev.com/', 'erp', 'netwolf103');
        $r = $client->callSalesOrderPaymentTransactionInfo('100000086');
        print_r($r);exit;
        
        $api = new Tracking('AVlesybUTgKTX8d_A1vyvlcRNsJ8Q_EO6bi1ZNM0hIJ4zkuhtlKGYmu_xbh7AVUw-B9tIwyWCmvGZXYm', 'ED1r0xRkySLgHrIJOpmc_dAW4ph0jAcO8izJVPkjzxVmI9tkCJtbCCK0pqV7Xuj2LeQxJAuzhQmu6Ir3', true);

        $r = $api->add([
            'transaction_id' => '0MN53398NV651115U',
            'tracking_number' => 'test001',
            'status' => 'SHIPPED',
            'carrier' => 'OTHER',
            'carrier_name_other' => 'YUNEXPRESS'
        ]);
        
        //$api->info('0MN53398NV651115U', 'test001');
        //
        print_r($r);

        $io->success('Done.');
    }
}
