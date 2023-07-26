<?php

namespace App;

use \koolreport\dashboard\Application;

use \koolreport\dashboard\menu\Group;
use \koolreport\dashboard\pages\Login;
use \koolreport\dashboard\User;

use \koolreport\dashboard\Client;
use \koolreport\dashboard\ExportHandler;
use \koolreport\dashboard\export\LocalExport;
use \koolreport\dashboard\export\ChromeHeadlessio;
use \koolreport\dashboard\export\CSVEngine;
use \koolreport\dashboard\export\XLSXEngine;

use \koolreport\dashboard\menu\MegaMenu;
use \koolreport\dashboard\menu\MenuItem;

use \koolreport\amazing\dashboard\Amazing;
use \koolreport\appstack\dashboard\AppStack;
use \koolreport\dashboard\Cookie;
use \koolreport\dashboard\languages\DE;
use \koolreport\dashboard\languages\EN;
use \koolreport\dashboard\languages\FR;
use \koolreport\dashboard\languages\TH;

use Illuminate\Support\Facades\Auth;

class App extends Application
{
    protected function onCreated()
    {
        $this->debugMode(true)
        ->js("//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.0.1/highlight.min.js")
        ->css("https://cdn.koolreport.com/examples/assets/theme/tomorrow.css");

    }

    /**
     * This event is called when application is initiated
     * We will look for the theme from cookie to set
     * @return void
     */
    protected function onInit()
    {
        $themeName = Cookie::themeName();
        $themeName = ($themeName!==null)?$themeName:"Amazing";
        $this->setTheme($themeName);
    }

    protected function login()
    {
        return  Login::create()
                ->descriptionText("
                    <i style='color:#333'>
                    Please log in with <b class='text-danger'>hello@eyesimple.us</b>/<b class='text-danger'>12341234</b>
                    </i>
                ")
                ->failedText("Wrong! Please use <b>hello@eyesimple.us</b> for username and <b>12341234</b> for password!")
                ->authenticate(function ($username, $password) {

                    if (Auth::attempt(['email' => $username, 'password' => $password])) {
                        // If authenticated, return User object
                        return User::create()
                            ->id(1)
                            ->name("Admin")
                            ->avatar("images/8.jpg")
                            ->roles(["admin"]);
                    }

                    if($username == 'hello@eyesimple.us' && $password == '12341234') {
                        return User::create()
                            ->id(1)
                            ->name("Admin")
                            ->avatar("images/8.jpg")
                            ->roles(["admin"]);
                    }

                    //Other: fail to login, return null
                    return null;
                });
    }

    /**
     * Provide list of languages for user to choose
     * @return array
     */
    protected function languages()
    {
        return [
            EN::create()
        ];
    }


    /**
     * Return list of all pages.
     * Here will have two pages: PublicPage which user can access freely
     * and MemberPage which will required user to login access
     * @return array
     */
    protected function pages()
    {
        return [
            PublicPage::create(),
            // MemberPage::create(),
        ];
    }

    /**
     * This action will get the user request of changing theme from client
     * it will set the theme as well as save the current theme selection
     * to cookie. Last, it will reload page to get theme changed.
     * @param mixed $request
     * @param mixed $response
     * @return void
     */
    protected function actionChangeTheme($request, $response)
    {
        $themeName = $request->params("name");
        $this->setTheme($themeName);
        Cookie::themeName($themeName);
        $response->runScript("location.reload()");
    }

    /**
     * Just a function to set correct theme for App
     * from the name of theme
     * @param mixed $name
     * @return void
     */
    protected function setTheme($name)
    {
        $this->theme(AppStack::create());
    }

    /**
     * Account menu that will appear when user login
     * We will use the same account menu on all pages
     * @return array
     */
    protected function accountMenu()
    {
        return [
            "Logout"=>MenuItem::create()
                ->icon("fa fa-lock")
                ->onClick(Client::logout())
        ];
    }

    protected function export()
    {
        return ExportHandler::create()
                ->storage(dirname(__DIR__)."/public/files/exports")
                ->csvEngine(
                    CSVEngine::create()
                )
                ->xlsxEngine(
                    XLSXEngine::create()
                )
                ->pdfEngine(
                    ChromeHeadlessio::create()->token("e6b3bbd783b447ec706a399d3c63761958ecd4cfac730cb10c0265a54b6296e0")
                    ->defaultConfig([
                      "scale"=>0.5,
                      "format"=>"A4",
                      "margin"=>[
                        "top"=>"1in",
                        "bottom"=>"1in",
                        "left"=>"1in",
                        "right"=>"1in",
                      ],
                    ])
                );
                // ->pdfEngine(
                //     LocalExport::create()
                //     ->defaultConfig([
                //         "format"=>"A4",
                //         "orientation"=>"landscape"
                //     ])
                // );
    }
}
