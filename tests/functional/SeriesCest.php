<?php

class SeriesCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('shows/series');
    }

    public function openSeriesPage(\FunctionalTester $I)
    {
        $I->see('Series', 'h1');
    }

    public function openSeriesElement(\FunctionalTester $I)
    {
        $I->click('Ver');
        $I->see('Jessica Jones', 'td');
    }
}
