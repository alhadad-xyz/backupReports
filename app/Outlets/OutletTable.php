<?php
namespace App\Outlets;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class OutletTable extends Table
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
                    Html::h1("Outlet List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("outlets")
            ->leftJoin('distributors', 'distributors.distributor_id', 'outlets.distributor_id')
            ->select('outlets.outlet_id', 'outlets.distributor_id', 'outlets.outlet_name', 'outlets.outlet_city', 'outlets.outlet_address', 'outlets.outlet_contact_no', 'outlets.outlet_email', 'outlets.outlet_taxable_company', 'outlets.outlet_npwp_address', 'outlets.outlet_npwp_no')
            ->select('distributor_name');
    }

    protected function fields()
    {
        return [
            Text::create("outlet_name")
                ->label('Nama')
                ->searchable(true)
                ->sortable(true),
            Text::create("distributor_name")
                ->label('Distributor')
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet_city")
                ->label("Kota")
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet_address")
                ->label("Alamat"),
            Text::create("outlet_contact_no")
                ->label("No Telp"),
            Text::create("outlet_email")
                ->label("Email")
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet_taxable_company")
                ->label("Nama Pengusaha Kena Pajak"),
            Text::create("outlet_npwp_address")
                ->label("Alamat NPWP")
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet_npwp_no")
                ->label("No NPWP")
                ->searchable(true)
                ->sortable(true),
        ];
    }
}
