<?php

use PHPUnit\Framework\TestCase;

use Morphism\Morphism;

class MorphismArrayTest extends TestCase
{
    public function setUp(){
        $this->data          = array(
            array(
                "name"      => "Iron Man",
                "firstName" => "Tony",
                "lastName"  => "Stark",
                "address" => array(
                    "city"    => "New York City",
                    "country" => "USA"
                ),
                "phoneNumber" => array(
                    array(
                        "type"   => "home",
                        "number" => "212 555-1234"
                    ),
                    array(
                        "type"   => "mobile",
                        "number" => "646 555-4567"
                    )
                )
            ),
            array(
                "name"      => "Spiderman",
                "firstName" => "Peter",
                "lastName"  => "Parker",
                "address" => array(
                    "city"    => "New York City",
                    "country" => "USA"
                ),
                "phoneNumber" => array(
                    array(
                        "type"   => "home",
                        "number" => "293 093-2321"
                    )
                )
            )
        );
    }


    public function testActionArrayStringPath()
    {
        $schema = array(
            "city" => "address.city"
        );

        if(!Morphism::exists("User")){
            Morphism::setMapper("User", $schema);
        }

        $result = Morphism::map("User", $this->data);
    
        $this->assertEquals(count($result), 2);
        $this->assertEquals($result[0]->city, "New York City");
    }

}