<?php
namespace App\Distributors;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\fields\DateTime;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class DistributorTable extends Table
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
                    Html::h1("Distributors List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("distributors");
    }

    protected function fields()
    {
        return [
            Text::create("distributor_name")
                ->label('Nama')
                ->searchable(true)
                ->sortable(true),
            Text::create("distributor_city")
                ->label("Kota")
                ->searchable(true)
                ->sortable(true),
            Text::create("distributor_address")
                ->label("Alamat"),
            Text::create("distributor_contact_no")
                ->label("No Telp"),
            Text::create("distributor_email")
                ->label("Email")
                ->searchable(true)
                ->sortable(true),
            Text::create("distributor_taxable_company")
                ->label("Nama Pengusaha Kena Pajak"),
            Text::create("distributor_npwp_address")
                ->label("Alamat NPWP")
                ->searchable(true)
                ->sortable(true),
            Text::create("distributor_npwp_no")
                ->label("No NPWP")
                ->searchable(true)
                ->sortable(true),
        ];
    }
}
