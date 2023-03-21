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
        return AutoMaker::table("users")
            ->leftJoin('distributor_has_outlets as dho', 'users.id', 'dho.outlet_id')
            ->leftJoin('users as distributor', 'distributor.id', 'dho.distributor_id')
            ->where('users.type', 'outlet')
            ->select('users.*', 'distributor.name as distributor');
    }

    protected function fields()
    {
        return [
            Text::create("name")
                ->label('Nama')
                ->searchable(true)
                ->sortable(true),
            Text::create("distributor")
                ->label('Distributor')
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
