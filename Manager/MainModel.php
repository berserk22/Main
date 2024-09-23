<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Manager;

use Core\Config\Config;
use DI\DependencyException;
use DI\NotFoundException;
use Modules\Cars\Manager\CarsManager;
use Modules\Main\MainTrait;
use Modules\Product\Manager\ProductManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Http\ServerRequest as Request;

class MainModel {

    use MainTrait;

    /**
     * @var Config|null
     */
    private ?Config $config = null;

    /**
     * @var array|string[]
     */
    private array $chars = [
        'ö', 'ä', 'ü', 'ß', 'Ö', 'Ä', 'Ü', ' ', '_', '#', '.'
    ];

    /**
     * @var array|string[]
     */
    private array $replaceChars = [
        'oe', 'ae', 'ue', 'ss', 'Oe', 'Ae', 'Ue', '-', '-', '-', '-'
    ];

    /**
     * @param string $title
     * @return string
     */
    public function changeChars(string $title = ''): string {
        $title = str_replace($this->chars, $this->replaceChars, $title);
        return strtolower($title);
    }

    /**
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getLang(): string {
        $lang = 'de';
        if ($this->getContainer()->has('Session\Manager')){
            $session = $this->getContainer()->get('Session\Manager');
            if ($session->has('lang')){
                $lang=$session->get('lang');
            }
        }
        else {
            $config = $this->getContainer()->get('config')->getSetting('lang');
            if (!empty($config) && isset($config['default'])){
                $lang=$config['default'];
            }
        }
        return $lang;
    }

    /**
     * @param int $page_id
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getParents(int $page_id): array {
        $parents = [];
        $page = $this->getMainManager()->getPageEntity()::select('id', 'name', 'title', 'parent_id')->find($page_id);
        if ($page->parent_id !== 0){
            $parent_page = $this->getMainManager()->getPageEntity()::select('id', 'name', 'title', 'parent_id')
                ->find($page->parent_id);
            $check_parent = $this->getParents($page->parent_id);
            $parents[$parent_page->title] = ['main_page', ['page'=>$parent_page->name]];
            if (!empty($check_parent)){
                $parents = array_merge($check_parent, $parents);
            }
        }
        return $parents;
    }

    /**
     * @param int|string $group
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getSettings(int|string $group): array {
        if (is_int($group)){
            $settingsGroup = $this->getMainManager()->getSettingsGroupEntity()::find($group);
        }
        else {
            $settingsGroup = $this->getMainManager()->getSettingsGroupEntity()::where("key", "=", $group)->get();
        }
        $settingsArray=[];
        foreach($settingsGroup->getSettings() as $setting){
            $settingsArray[str_replace($settingsGroup->key."_", "", $setting->key)] = $setting->value;
        }
        return $settingsArray;
    }

    /**
     * @param int|string|null $group
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getPageGroup(int|string $group = null): mixed {
        $pageGroups = null;
        if (is_null($group)){
            $pageGroups = $this->getMainManager()->getPageGroupEntity()::where('status', '=', 'publish')
                ->whereNot('name', '=', 'default')->get();
        }
        elseif(is_int($group)){
            $pageGroups =  $this->getMainManager()->getPageGroupEntity()::find($group);
        }
        elseif($group === "all"){
            $pageGroups =  $this->getMainManager()->getPageGroupEntity()::where('status', '=', 'publish')->get();
        }
        return $pageGroups;
    }

    /**
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getSitemapArray(): array {
        $domainSetting = $this->getConfig("domain");

        $pages = $this->getMainManager()->getPageEntity()::select("name", "updated_at")
            ->where("status", "=", "publish")->get();
        $page_liste[] = $domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                ->getUrl("main_home");
        foreach ($pages as $page){
            $page_liste[]=$domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                    ->getUrl("main_page", ["page"=>$page->name]);
        }
        /**
         * @var CarsManager $carsManager
         */
        $car_liste = [];
        if ($this->getContainer()->has('Cars\Manager')){
            $carsManager = $this->getContainer()->get('Cars\Manager');
            $cars = $carsManager->getCarsEntity()::select("gtin", "updated_at")->where('status', '=', 1)->get();
            foreach ($cars as $car){
                $car_liste[]=$domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                        ->getUrl("cars_details", ["carId"=>$car->gtin]);
            }
        }
        $productList = [];
        if ($this->getContainer()->has('Product\Manager')){
            /** @var ProductManager $productManager */
            $productManager = $this->getContainer()->get('Product\Manager');

            $groupList = $productManager->getAttributeGroupEntity()::where("status", "=", "publish")->get();
            foreach($groupList as $group){
                $productList[]=$domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                        ->getUrl("product_group", ["group"=>$group->name]);
            }

            $categoryList = $productManager->getCategoryEntity()::where("status", "=", "publish")->get();
            foreach($categoryList as $category){
                $productList[]=$domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                        ->getUrl("product_category", ["category"=>$category->name]);
            }

            $manufacturerList = $productManager->getManufacturerEntity()::where("status", "=", "publish")->get();
            foreach($manufacturerList as $manufacturer){
                $productList[]=$domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                        ->getUrl("product_manufacturer", ["manufacturer"=>$manufacturer->name]);
            }

            $products = $productManager->getProductEntity()::where("status", "=", "publish")->get();
            foreach($products as $product){
                $productList[]=$domainSetting["protocol"].'://'.$domainSetting["name"].$this->getMainRouter()
                        ->getUrl("product_detail", [
                            "productName"=>$product->name,
                            "vendorNumber"=>$product->vendor_number
                        ]);
            }
        }

        return array_merge($page_liste, $productList, $car_liste);
    }

    /**
     * @param string $group
     * @param string $key
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getConfig(string $group = "", string $key = ""): mixed {
        if (is_null($this->config)){
            $this->config = $this->getContainer()->get("config");
        }
        $config = $this->config->getSetting();
        if (!empty($group)){
            if (!empty($key)){
                $config = $this->config->getSetting($group)[$key];
            }
            else {
                $config = $this->config->getSetting($group);
            }
        }
        return $config;
    }

}
