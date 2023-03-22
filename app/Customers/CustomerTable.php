<?php
namespace App\Customers;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class CustomerTable extends Table
{
    protected function onInit()
    {
        $this->pageSize(10);
    }

    protected function onExporting($params)
    {
        //Remove table paging when exporting to PDF
        $this->pageSize(null);
        return true;
    }

    public function exportedView()
    {
        return  Html::div([
                    Html::h1("Customer List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("customers")
            ->leftJoin('outlets', 'outlets.outlet_id', 'outlets.outlet_id')
            ->select('customers.customer_id', 'customers.outlet_id', 'customers.customer_name', 'customers.customer_city', 'customers.customer_address', 'customers.customer_contact_no', 'customers.customer_email', 'customers.customer_taxable_company', 'customers.customer_npwp_address', 'customers.customer_npwp_no')
            ->select('outlet_name');
    }

    protected function fields()
    {
        return [
            Text::create("name")
                ->label('Nama')
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet")
                ->label('Outlet')
                ->searchable(true)
                ->sortable(true),
            Text::create("city")
                ->label("Kota")
                ->searchable(true)
                ->sortable(true),
            Text::create("address")
                ->label("Alamat"),
            Text::create("contact_no")
                ->label("No Telp"),
            Text::create("email")
                ->label("Email")
                ->searchable(true)
                ->sortable(true),
            Text::create("taxable_company")
                ->label("Nama Pengusaha Kena Pajak"),
            Text::create("npwp_address")
                ->label("Alamat NPWP")
                ->searchable(true)
                ->sortable(true),
            Text::create("npwp_no")
                ->label("No NPWP")
                ->searchable(true)
                ->sortable(true),
        ];
    }
}
